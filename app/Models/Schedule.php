<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 'classroom_id', 'teacher_id',
        'type', 'day', 'start_time', 'end_time', 'section','year','group'
    ];

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function classroom() {
        return $this->belongsTo(Classroom::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}

