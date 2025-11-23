<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowHistory extends Model
{
    protected $table = 'borrow_history';

    protected $fillable = [
        'borrower_id',
        'user_id',
        'resource_id',
        'action_by',
        'action',
        'rejection_reason',
        'approved_at',
        'return_date',
        'returned_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'return_date' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function borrower(): BelongsTo
    {
        return $this->belongsTo(Borrower::class, 'borrower_id', 'Borrower_ID');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class, 'resource_id', 'Resource_ID');
    }

    public function actionBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
