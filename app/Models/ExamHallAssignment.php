<?php

// app/Models/ExamHallAssignment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamHallAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'classroom_id',
        'student_id',
        'seat_number',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
