<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
     protected $fillable = [
        'course_id', 'exam_date', 'start_time', 'end_time', 'type','target_year',
    ];

    public function course()
{
    return $this->belongsTo(Course::class);
}

public function results()
{
    return $this->hasMany(ExamResult::class);
}

public function rooms()
{
    return $this->hasMany(ExamHallAssignment::class);
}

}
