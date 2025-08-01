<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mental_health_assessment', function (Blueprint $table) {
            $table->id();
            $table->json('stress');
            $table->json('anxiety');
            $table->json('depression');
            $table->timestamps();
            
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mental_health_assessment');
    }
};