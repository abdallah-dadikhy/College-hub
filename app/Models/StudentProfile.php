<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'mother_name',
        'birth_date',
        'birth_place',
        'department',
        'high_school_gpa',
        'photo_path',
        'university_id'
    ];

    public function student()
{
    return $this->belongsTo(Student::class, 'university_id', 'university_id');
}
}

