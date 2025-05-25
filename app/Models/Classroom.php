<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type'];

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
    public function rooms()
{
    return $this->hasMany(ExamHallAssignment::class);
}
}

