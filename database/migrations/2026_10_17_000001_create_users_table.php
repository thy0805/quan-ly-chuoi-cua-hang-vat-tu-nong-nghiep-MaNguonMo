<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('username', 80)->collation('utf8mb4_0900_bin')->unique();
            $table->text('password_hash');
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->boolean('is_active')->default(true);
            $table->dateTime('created_at')->useCurrent();
            $table->tinyInteger('admin_slot')->nullable()->storedAs("CASE WHEN role = 'admin' THEN 1 ELSE NULL END")->unique();
        });
        DB::statement('ALTER TABLE users ADD CONSTRAINT users_active_check CHECK (is_active IN (0, 1))');
    }

    public function down(): void
    {
        Schema::drop('users');
    }
};
