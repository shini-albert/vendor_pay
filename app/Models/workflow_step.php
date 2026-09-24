<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowStep extends Model
{
    protected $fillable = [
        'workflow_id',
        'step_no',
        'role_id',
        'step_name',
    ];

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}

