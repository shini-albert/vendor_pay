<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workflow_Step extends Model
{
    protected $table = 'workflow_steps';
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
    public function workflowsteps()
    {
        return $this->hasMany(Workflow_Step::class);
    }

}

