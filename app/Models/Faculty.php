<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $table = 'faculty';
    protected $primaryKey = 'Faculty_ID';
    protected $fillable = ['UID', 'First_Name', 'Last_Name'];

    public function user()
    {
        return $this->belongsTo(User::class, 'UID');
    }
}