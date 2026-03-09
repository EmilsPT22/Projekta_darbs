<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'internship_id',
        'theme_id',
        'date',
        'location',
        'time_from',
        'time_to',
        'duration',
        'credit_hours',
        'intern_comment',
        'org_supervisor_comment',
        'admin_comment',
        'grade',
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }
}
