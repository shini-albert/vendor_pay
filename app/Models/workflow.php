<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workflow extends Model
{
    use HasFactory;

    protected $table = 'workflows';

    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];

    public function rules()
    {
        return $this->hasMany(workflow_rule::class, 'workflow_id');
    }

    public function steps()
    {
        return $this->hasMany(Workflow_Step::class, 'workflow_id')->orderBy('step_no', 'asc');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'workflow_id');
    }
}