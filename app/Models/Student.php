<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'student';
    protected $primaryKey = 'UID';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['UID', 'First_Name', 'Last_Name'];

    // FIXED: foreign key = UID, local key = id
    public function user()
    {
        return $this->belongsTo(User::class, 'UID', 'id');
    }
}