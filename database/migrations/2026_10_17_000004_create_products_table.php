<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('category_id')->index();
            $table->bigInteger('unit_id')->index();
            $table->string('code', 60)->collation('utf8mb4_0900_bin')->unique();
            $table->string('name', 200);
            $table->string('active_ingredient', 200)->nullable();
            $table->decimal('sale_price', 18, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->integer('expiry_warning_days')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreign('category_id')->references('id')->on('product_categories')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('unit_id')->references('id')->on('units')->restrictOnDelete()->restrictOnUpdate();
        });
        DB::statement('ALTER TABLE products ADD CONSTRAINT products_price_check CHECK (sale_price >= 0), ADD CONSTRAINT products_tax_check CHECK (tax_rate IS NULL OR tax_rate BETWEEN 0 AND 100), ADD CONSTRAINT products_expiry_check CHECK (expiry_warning_days IS NULL OR expiry_warning_days BETWEEN 0 AND 3650), ADD CONSTRAINT products_active_check CHECK (is_active IN (0, 1))');
    }

    public function down(): void
    {
        Schema::drop('products');
    }
};
