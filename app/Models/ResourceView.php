<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceView extends Model
{
    protected $fillable = [
        'resource_id',
        'user_id',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id', 'Resource_ID');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}