<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Librarian extends Model
{
    protected $table = 'librarian';
    protected $primaryKey = 'UID';
    public $incrementing = false;
    protected $fillable = ['UID', 'First_Name', 'Last_Name'];

    public function user()
    {
        return $this->belongsTo(User::class, 'UID');
    }
}