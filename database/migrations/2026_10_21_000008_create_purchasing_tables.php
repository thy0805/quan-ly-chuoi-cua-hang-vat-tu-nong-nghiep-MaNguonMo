<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('code', 40)->unique();
            $table->string('name', 200);
            $table->string('phone', 30)->nullable();
            $table->string('email', 200)->nullable();
            $table->string('address', 500)->nullable();
            $table->boolean('is_active')->default(true);
        });
        DB::statement('ALTER TABLE suppliers ADD CONSTRAINT suppliers_active_check CHECK (is_active IN (0,1)), ADD CONSTRAINT suppliers_identity_check CHECK (CHAR_LENGTH(TRIM(code)) > 0 AND CHAR_LENGTH(TRIM(name)) > 0)');
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('user_id')->index();
            $table->bigInteger('supplier_id')->index();
            $table->string('supplier_code', 40);
            $table->string('supplier_name', 200);
            $table->string('supplier_phone', 30)->nullable();
            $table->string('supplier_address', 500)->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->restrictOnDelete()->restrictOnUpdate();
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
        Schema::create('purchase_lines', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('purchase_id');
            $table->bigInteger('product_id');
            $table->string('product_code', 80);
            $table->string('product_name', 255);
            $table->string('unit_name', 255);
            $table->decimal('quantity', 15, 3);
            $table->decimal('unit_price', 18, 2);
            $table->decimal('line_total', 18, 2);
            $table->unique(['purchase_id', 'product_id']);
            $table->foreign('purchase_id')->references('id')->on('purchases')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('product_id')->references('id')->on('products')->restrictOnDelete()->restrictOnUpdate();
        });
        DB::statement("ALTER TABLE purchases ADD CONSTRAINT purchases_total_check CHECK (total >= 0), ADD CONSTRAINT purchases_version_check CHECK (version > 0), ADD CONSTRAINT purchases_state_check CHECK ((status='draft' AND confirmed_at IS NULL) OR (status='confirmed' AND confirmed_at IS NOT NULL))");
        DB::statement('ALTER TABLE purchase_lines ADD CONSTRAINT purchase_line_values_check CHECK (quantity > 0 AND unit_price >= 0 AND line_total >= 0), ADD CONSTRAINT purchase_line_total_check CHECK (line_total = ROUND(quantity * unit_price, 2))');
        foreach (['UPDATE', 'DELETE'] as $event) {
            DB::unprepared("CREATE TRIGGER purchases_no_".strtolower($event)." BEFORE $event ON purchases FOR EACH ROW BEGIN IF OLD.status='confirmed' THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='Confirmed purchase is immutable'; END IF; END");
        }
        foreach (['INSERT', 'UPDATE', 'DELETE'] as $event) {
            $row = $event === 'INSERT' ? 'NEW' : 'OLD';
            $condition = "(SELECT status FROM purchases WHERE id=$row.purchase_id)='confirmed'";
            if ($event === 'UPDATE') $condition .= " OR NEW.purchase_id <> OLD.purchase_id";
            DB::unprepared("CREATE TRIGGER purchase_lines_no_".strtolower($event)." BEFORE $event ON purchase_lines FOR EACH ROW BEGIN IF $condition THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='Confirmed purchase lines are immutable'; END IF; END");
        }
    }

    public function down(): void
    {
        Schema::drop('purchase_lines');
        Schema::drop('purchases');
        Schema::drop('suppliers');
    }
};
