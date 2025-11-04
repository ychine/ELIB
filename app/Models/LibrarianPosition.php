<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibrarianPosition extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'permissions', 'created_by'];

    protected $casts = [
        'permissions' => 'array', // Auto-decodes JSON to PHP array
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function librarians()
    {
        return $this->hasMany(Librarian::class, 'position_id');
    }
}