<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('product_id')->unique();
            $table->decimal('quantity', 15, 3)->default(0);
            $table->dateTime('updated_at', 6);
            $table->foreign('product_id')->references('id')->on('products')->restrictOnDelete()->restrictOnUpdate();
        });
        DB::statement('ALTER TABLE product_stocks ADD CONSTRAINT stocks_quantity_check CHECK (quantity >= 0)');
    }

    public function down(): void
    {
        Schema::drop('product_stocks');
    }
};
