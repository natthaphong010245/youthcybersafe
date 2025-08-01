<!-- database/migrations/2025_03_05_081032_add_role_school_lastname_to_users_table.php -->
<?php

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
    // ถ้า role_user ยังไม่มีในตาราง users
    if (!Schema::hasColumn('users', 'role_user')) {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('role_user')->default(0)->after('role');
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role_user');
        });
    }
};