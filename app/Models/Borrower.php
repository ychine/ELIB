<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    protected $table = 'borrower';

    protected $primaryKey = 'Borrower_ID';

    protected $fillable = [
        'UID',
        'resource_id',
        'Approved_By',
        'Approved_Date',
        'Return_Date',
        'isReturned',
        'rejection_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UID');
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id', 'Resource_ID');
    }
}
