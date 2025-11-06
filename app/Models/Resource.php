<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'Resource_Name', 'File_Path', 'Type', 'Publish_Date', 'Uploaded_By', 'Description', 'status', 'author'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'Uploaded_By');
    }
}