<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('product_id')->index();
            $table->bigInteger('user_id')->index();
            $table->enum('direction', ['in', 'out']);
            $table->decimal('quantity', 15, 3);
            $table->decimal('quantity_before', 15, 3);
            $table->decimal('quantity_after', 15, 3);
            $table->string('note', 1000)->nullable();
            $table->char('idempotency_key', 36)->collation('utf8mb4_0900_bin')->unique();
            $table->string('source_type', 32)->default('manual');
            $table->string('source_id', 64)->nullable();
            $table->unique(['source_type', 'source_id']);
            $table->dateTime('created_at', 6)->index();
            $table->foreign('product_id')->references('id')->on('products')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete()->restrictOnUpdate();
        });
        DB::statement("ALTER TABLE inventory_movements ADD CONSTRAINT movement_quantity_check CHECK (quantity > 0 AND quantity_before >= 0 AND quantity_after >= 0), ADD CONSTRAINT movement_balance_check CHECK (quantity_after = quantity_before + IF(direction = 'in', quantity, -quantity)), ADD CONSTRAINT movement_source_check CHECK ((source_type = 'manual' AND source_id IS NULL) OR (source_type IN ('sale', 'purchase') AND source_id IS NOT NULL))");
        foreach (['UPDATE', 'DELETE'] as $event) {
            DB::unprepared("CREATE TRIGGER inventory_no_".strtolower($event)." BEFORE $event ON inventory_movements FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Inventory history is immutable'");
        }
    }

    public function down(): void
    {
        Schema::drop('inventory_movements');
    }
};
