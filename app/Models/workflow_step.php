<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workflow_Step extends Model
{
    protected $table = 'workflow_steps';
    
    protected $fillable = [
        'workflow_id',
        'rule_id', 
        'step_no',
        'role_id',
        'step_name',
    ];

    public function workflow()
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    public function rule()
    {
        return $this->belongsTo(workflow_rule::class, 'rule_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function paymentApprovals()
    {
        return $this->hasMany(PaymentApproval::class, 'workflow_step_id');
    }
}