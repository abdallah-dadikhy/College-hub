<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'department', 'year'];

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
    public function teachers()
{
    return $this->belongsToMany(Teacher::class, 'course_teacher');
}

}

