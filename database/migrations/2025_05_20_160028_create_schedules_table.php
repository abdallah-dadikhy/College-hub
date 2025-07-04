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
        Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->foreignId('course_id')->constrained()->onDelete('cascade');
        $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
        $table->foreignId('teacher_id')->nullable()->constrained()->onDelete('set null');

        $table->enum('type', ['theory', 'lab']);
        $table->string('day'); // السبت - الأحد - ...
        $table->time('start_time');
        $table->time('end_time');
        $table->string('year')->nullable();  // أولى/ثانية...
        $table->string('group')->nullable(); // للفئة A/B/...
        $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
