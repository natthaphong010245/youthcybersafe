<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('safe_area', function (Blueprint $table) {
            $table->id();
            $table->json('voice_message');
            $table->timestamps();
            
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('safe_area');
    }
};