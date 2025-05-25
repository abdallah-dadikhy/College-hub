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
    Schema::create('teacher_profiles', function (Blueprint $table) {
        $table->id();
        $table->string('full_name');
        $table->string('mother_name');
        $table->date('birth_date');
        $table->string('birth_place');
        $table->string('academic_degree');
        $table->string('degree_source');
        $table->string('department');
        $table->string('position'); // دكتور، معيد...
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_profiles');
    }
};
