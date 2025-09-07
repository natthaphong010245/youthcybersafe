<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('role')->default('user');
            $table->boolean('role_user')->default(0);
            $table->string('school')->nullable();
            $table->string('name');
            $table->string('lastname');
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamps();
            
            // เพิ่ม index
            $table->index(['role', 'role_user']);
            $table->index('role_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};