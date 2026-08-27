<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'qr_code_token',
        'certificate_fee_paid',
        'certificate_fee_txn',
        'registered_at',
        'cancelled_at',
    ];

    protected $casts = [
        'certificate_fee_paid' => 'boolean',
        'registered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
