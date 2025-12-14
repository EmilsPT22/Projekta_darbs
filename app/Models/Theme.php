<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'internship_id',
        'name',
        'max_hours',
        'description',
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }

    public function entries()
    {
        return $this->hasMany(DailyEntry::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['assigned_hours', 'used_hours'])
            ->withTimestamps();
    }

    public function remainingHoursForUser($userId)
    {
        $pivot = $this->users()
            ->where('user_id', $userId)
            ->first()
            ?->pivot;

        return $pivot
            ? $pivot->assigned_hours - $pivot->used_hours
            : 0;
    }
}
