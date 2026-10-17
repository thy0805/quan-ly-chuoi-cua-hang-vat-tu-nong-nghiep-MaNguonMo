<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Support\MigrationTriggers;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('parent_id')->nullable()->index();
            $table->string('code', 40)->collation('utf8mb4_0900_bin')->unique();
            $table->string('name', 160);
            $table->boolean('is_active')->default(true);
            $table->foreign('parent_id')->references('id')->on('product_categories')->restrictOnDelete()->restrictOnUpdate();
        });
        DB::statement('ALTER TABLE product_categories ADD CONSTRAINT categories_active_check CHECK (is_active IN (0, 1))');
        MigrationTriggers::apply('create');
    }

    public function down(): void
    {
        MigrationTriggers::apply('drop');
        Schema::drop('product_categories');
    }
};
