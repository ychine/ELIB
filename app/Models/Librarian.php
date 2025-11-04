<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Librarian extends Model
{
    use HasFactory;

    protected $table = 'librarian';
    protected $primaryKey = 'UID';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = ['UID', 'First_Name', 'Last_Name', 'position_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'UID', 'id');
    }

    // ADD THIS: The missing relationship
    public function position()
    {
        return $this->belongsTo(LibrarianPosition::class, 'position_id', 'id');
    }
}