<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    protected $table = 'ratings';
    public $timestamps = true;

    protected $fillable = [
        'resource_id',
        'user_id',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class, 'resource_id', 'Resource_ID');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}