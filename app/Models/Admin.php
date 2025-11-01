<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin'; 
    protected $primaryKey = 'UID';
    public $incrementing = false; 
    protected $keyType = 'int'; 

    protected $fillable = [
        'UID',
        'First_Name',
        'Last_Name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'UID');
    }
}