<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    protected $table = 'campus'; 
    protected $primaryKey = 'Campus_ID';
    protected $fillable = ['Campus_Name', 'created_at', 'updated_at'];
}