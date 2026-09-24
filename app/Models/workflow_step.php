<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workflow_step extends Model
{
    protected $fillable = [
        'workflow_id',
        'step_no',
        'role_id',
        'step_name',
    ];

    public function workflowrule()
    {
        return $this->belongsTo(workflow_rule::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}

