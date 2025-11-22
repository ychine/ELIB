<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;          // ← NEW
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail   // ← NEW
{
    use Notifiable;

    protected $fillable = [
        'first_name',         
        'last_name',
        'email',
        'password',
        'role',
        'Campus_ID',
        'is_approved',
        'email_verified_at',
        'profile_picture',
        'last_login_at',
        'is_online',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_approved' => 'boolean',
        'is_online' => 'boolean',
    ];

    /* ──────────────────────── RELATIONSHIPS ──────────────────────── */
    public function campus()
    {
        return $this->belongsTo(Campus::class, 'Campus_ID');
    }

    public function faculty()
    {
        return $this->hasOne(Faculty::class, 'UID', 'id');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'UID', 'id');
    }

    public function librarian()
    {
        return $this->hasOne(Librarian::class, 'UID', 'id');
    }

    public function librarianPosition() // Now direct via hasOneThrough if needed, but use librarian->position
    {
        return $this->hasOneThrough(LibrarianPosition::class, Librarian::class, 'UID', 'id', 'id', 'position_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'UID', 'id');
    }

    /** Dynamic profile relationship (admin / librarian / student / faculty) */
    public function profile()
    {
        $model = match ($this->role) {
            'admin'     => \App\Models\Admin::class,
            'librarian' => \App\Models\Librarian::class,
            'student'   => \App\Models\Student::class,
            'faculty'   => \App\Models\Faculty::class,
            default     => null,
        };

        return $model ? $this->hasOne($model, 'UID', 'id') : null;
    }

    public function getFullNameAttribute()
    {
        if ($this->role == 'student') {
            $relation = $this->student;
        } elseif ($this->role == 'faculty') {
            $relation = $this->faculty;
        } elseif ($this->role == 'librarian') {
            $relation = $this->librarian;
        } elseif ($this->role == 'admin') {
            $relation = $this->admin;
        } else {
            return 'Unknown';
        }

        return $relation ? $relation->First_Name . ' ' . $relation->Last_Name : 'Unknown';
    }
}