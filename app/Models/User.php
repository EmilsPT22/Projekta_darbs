<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'class_group_id',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function internships()
    {
        return $this->belongsToMany(Internship::class);
    }

    public function themes()
    {
        return $this->belongsToMany(Theme::class)
            ->withPivot(['assigned_hours', 'used_hours'])
            ->withTimestamps();
    }

    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class);
    }

    public function taughtClasses()
    {
        return $this->hasMany(ClassGroup::class, 'teacher_id');
    }

    public function classGroups()
    {
        return $this->hasMany(ClassGroup::class, 'teacher_id');
    }

    public function dailyEntries()
    {
        return $this->hasMany(DailyEntry::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function unreadNotificationCount()
    {
        return $this->unreadNotifications()->count();
    }
}
