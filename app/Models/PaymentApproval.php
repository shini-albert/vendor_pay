<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentApproval extends Model
{
    protected $fillable = [
        'payment_id',
        'user_id',
        'step_no',
        'status',
        'comments',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}