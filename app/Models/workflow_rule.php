<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class workflow_rule extends Model
{
    use HasFactory;
    protected $table = 'workflow_rules';
    protected $fillable = [
        'workflow_id',
        'operator',
        'value',
    ];
    public function workflow()
    {
        return $this->belongsTo(workflow::class);
    }
     public function workflowsteps()
    {
        return $this->hasMany(Workflow_Step::class);
    }
}
