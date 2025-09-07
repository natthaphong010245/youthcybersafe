<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
        });

        // ตรวจสอบและเพิ่ม index แยกต่างหาก
        $this->addIndexSafely();
    }

    /**
     * เพิ่ม index อย่างปลอดภัย
     */
    private function addIndexSafely()
    {
        $tableName = 'users';
        
        // ตรวจสอบ index ที่มีอยู่
        $indexes = DB::select("SHOW INDEX FROM {$tableName}");
        $existingIndexes = collect($indexes)->pluck('Key_name')->toArray();
        
        // เพิ่ม compound index ถ้ายังไม่มี
        if (!in_array('users_role_role_user_index', $existingIndexes)) {
            DB::statement("ALTER TABLE {$tableName} ADD INDEX users_role_role_user_index (role, role_user)");
        }
        
        // เพิ่ม single index ถ้ายังไม่มี  
        if (!in_array('users_role_user_index', $existingIndexes)) {
            DB::statement("ALTER TABLE {$tableName} ADD INDEX users_role_user_index (role_user)");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ลบ index (ใช้ try-catch เพื่อป้องกัน error)
            try {
                $table->dropIndex(['role', 'role_user']);
            } catch (\Exception $e) {
                // ignore if index doesn't exist
            }
            
            try {
                $table->dropIndex(['role_user']);
            } catch (\Exception $e) {
                // ignore if index doesn't exist
            }
        });
    }
};