<?php
// database/migrations/2025_03_24_000001_create_behavioral_report_table_with_status.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('behavioral_report', function (Blueprint $table) {
            $table->id();
            $table->string('who');                       // teacher or researcher
            $table->string('school')->nullable();        // null if 'who' is researcher
            $table->text('message');
            $table->string('voice')->nullable();
            $table->longText('image')->nullable();       // Store as JSON array
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('status')->default(false);   // เพิ่มคอลัม status พร้อมค่าเริ่มต้น
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('behavioral_report');
    }
};