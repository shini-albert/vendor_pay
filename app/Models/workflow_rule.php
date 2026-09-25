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
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    
    public function steps()
    {
        return $this->hasMany(Workflow_Step::class, 'workflow_id', 'id');
    }
   
}
