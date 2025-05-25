<?php

// database/migrations/xxxx_xx_xx_create_exam_hall_assignments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamHallAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::create('exam_hall_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->integer('seat_number');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_hall_assignments');
    }
}

