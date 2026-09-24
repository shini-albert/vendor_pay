<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class workflow_rule extends Model
{
    public function workflow()
    {
        return $this->belongsTo(workflow::class);
    }
     public function workflowsteps()
    {
        return $this->hasMany(Workflow_Step::class);
    }
}
