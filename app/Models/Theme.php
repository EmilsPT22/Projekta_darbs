<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'internship_id',
        'name',
        'max_hours',
        'description'
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }
}
