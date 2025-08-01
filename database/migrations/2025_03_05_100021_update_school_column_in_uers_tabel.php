<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    public function up(): void
    {
        $users = DB::table('users')->select('id', 'role_user')->get();
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role_user');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('role_user')->default(false)->after('password');
        });

        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['role_user' => $user->role_user == 1 ? true : false]);
        }
    }

    public function down(): void
    {
        $users = DB::table('users')->select('id', 'role_user')->get();
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role_user');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('role_user')->default(0)->after('password');
        });

        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['role_user' => $user->role_user ? 1 : 0]);
        }
    }
};