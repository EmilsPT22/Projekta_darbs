<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'grade_level',
        'description',
        'teacher_id',
    ];

    public function students()
    {
        return $this->hasMany(User::class, 'class_group_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function internships()
    {
        return $this->belongsToMany(Internship::class, 'internship_class_group')
            ->withTimestamps();
    }
}
