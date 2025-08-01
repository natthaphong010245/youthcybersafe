<?php
// database/migrations/2024_01_01_000001_update_users_table_for_admin.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // อัปเดต role enum เพื่อรองรับ admin
            $table->string('role')->change();
            
            // เพิ่ม index สำหรับ performance
            $table->index(['role', 'role_user']);
            $table->index('role_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ลบ index
            $table->dropIndex(['role', 'role_user']);
            $table->dropIndex(['role_user']);
        });
    }
};