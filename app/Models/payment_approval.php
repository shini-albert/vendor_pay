<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentApproval extends Model
{
    protected $fillable = [
        'payment_id',
        'workflow_step_id',
        'user_id',
        'role_id',
        'action',
        'remarks',
        'acted_at',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function workflowStep()
    {
        return $this->belongsTo(WorkflowStep::class, 'workflow_step_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}

