<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'length',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function students()
    {
        return $this->belongsToMany(User::class);
    }

    public function dailyEntries()
    {
        return $this->hasMany(DailyEntry::class);
    }
    
    public function themes()
    {
        return $this->hasMany(Theme::class);
    }

}
