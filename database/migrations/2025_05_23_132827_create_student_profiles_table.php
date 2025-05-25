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
    Schema::create('student_profiles', function (Blueprint $table) {
        $table->id();
        $table->string('full_name');
        $table->string('university_id')->unique();
        $table->foreign('university_id')->references('university_id')->on('students')->onDelete('cascade');
        $table->string('mother_name');
        $table->date('birth_date');
        $table->string('birth_place');
        $table->string('department');
        $table->float('high_school_gpa');
        $table->string('photo_path')->nullable(); 
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
