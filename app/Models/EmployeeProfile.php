<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'mother_name',
        'birth_date',
        'birth_place',
        'academic_degree',
        'department',
        'employment_date',
    ];
}

