<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InternshipApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_id',
        'user_id',
        'cover_letter',
        'motivation',
        'phone',
        'cv_path',
        'status',
        'manager_comment',
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
