<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'university_id', 'faculty', 'department', 'year', 'section'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function rooms()
{
    return $this->hasMany(ExamHallAssignment::class);
}
 public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }
    public function profile()
{
    return $this->hasOne(StudentProfile::class, 'university_id', 'university_id');
}

    
}

