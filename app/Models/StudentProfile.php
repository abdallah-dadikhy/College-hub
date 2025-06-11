<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


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
    protected $appends = ['photo_path_url'];
    public function getPhotoPathUrlAttribute() // تم تغيير اسم الدالة لتطابق السمة في $appends
    {
        $value = $this->attributes['photo_path']; // الوصول إلى القيمة الخام المخزنة في قاعدة البيانات

        if ($value && Storage::disk('public')->exists($value)) {
            return asset('storage/' . $value);
        }
        return null;
    }
    public function student()
{
    return $this->belongsTo(Student::class, 'university_id', 'university_id');
}
}

