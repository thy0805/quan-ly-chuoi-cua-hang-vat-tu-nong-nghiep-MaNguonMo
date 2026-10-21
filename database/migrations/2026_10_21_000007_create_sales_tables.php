<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('user_id')->index();
            $table->char('client_key', 36)->collation('utf8mb4_0900_bin')->unique();
            $table->char('payload_hash', 64);
            $table->enum('status', ['draft', 'confirmed'])->default('draft')->index();
            $table->unsignedInteger('version')->default(1);
            $table->decimal('total', 18, 2);
            $table->string('note', 1000)->nullable();
            $table->dateTime('confirmed_at', 6)->nullable();
            $table->timestamps(6);
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete()->restrictOnUpdate();
        });
        Schema::create('sale_lines', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('sale_id');
            $table->bigInteger('product_id');
            $table->string('product_code', 80);
            $table->string('product_name', 255);
            $table->string('unit_name', 255);
            $table->decimal('quantity', 15, 3);
            $table->decimal('unit_price', 18, 2);
            $table->decimal('line_total', 18, 2);
            $table->unique(['sale_id', 'product_id']);
            $table->foreign('sale_id')->references('id')->on('sales')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('product_id')->references('id')->on('products')->restrictOnDelete()->restrictOnUpdate();
        });
        DB::statement("ALTER TABLE sales ADD CONSTRAINT sales_total_check CHECK (total >= 0), ADD CONSTRAINT sales_version_check CHECK (version > 0), ADD CONSTRAINT sales_state_check CHECK ((status='draft' AND confirmed_at IS NULL) OR (status='confirmed' AND confirmed_at IS NOT NULL))");
        DB::statement('ALTER TABLE sale_lines ADD CONSTRAINT sale_line_values_check CHECK (quantity > 0 AND unit_price >= 0 AND line_total >= 0), ADD CONSTRAINT sale_line_total_check CHECK (line_total = ROUND(quantity * unit_price, 2))');
        foreach (['UPDATE', 'DELETE'] as $event) {
            DB::unprepared("CREATE TRIGGER sales_no_".strtolower($event)." BEFORE $event ON sales FOR EACH ROW BEGIN IF OLD.status='confirmed' THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='Confirmed sale is immutable'; END IF; END");
        }
        foreach (['INSERT', 'UPDATE', 'DELETE'] as $event) {
            $row = $event === 'INSERT' ? 'NEW' : 'OLD';
            $condition = "(SELECT status FROM sales WHERE id=$row.sale_id)='confirmed'";
            if ($event === 'UPDATE') $condition .= " OR NEW.sale_id <> OLD.sale_id";
            DB::unprepared("CREATE TRIGGER sale_lines_no_".strtolower($event)." BEFORE $event ON sale_lines FOR EACH ROW BEGIN IF $condition THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='Confirmed sale lines are immutable'; END IF; END");
        }
    }

    public function down(): void
    {
        Schema::drop('sale_lines');
        Schema::drop('sales');
    }
};
