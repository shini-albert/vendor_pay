<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class workflow_rule extends Model
{
    protected $table = 'workflow_rule';
    protected $fillable = [
        'workflow_id',
        'field',
        'oeprator',
        'value',
    ];    
    public function workflow()
    {
        return $this->belongsTo(workflow::class);
    }
    
}
