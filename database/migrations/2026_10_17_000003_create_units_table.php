<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('code', 40)->collation('utf8mb4_0900_bin')->unique();
            $table->string('name', 100);
            $table->boolean('is_active')->default(true);
        });
        DB::statement('ALTER TABLE units ADD CONSTRAINT units_active_check CHECK (is_active IN (0, 1))');
    }

    public function down(): void
    {
        Schema::drop('units');
    }
};
