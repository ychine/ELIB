<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'email',
        'password',
        'role',
        'Campus_ID',
        'is_approved',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class, 'Campus_ID');
    }

    public function faculty()
    {
        return $this->hasOne(Faculty::class, 'UID');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'UID');
    }

    public function librarian()
    {
        return $this->hasOne(Librarian::class, 'UID');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'UID');
    }
}