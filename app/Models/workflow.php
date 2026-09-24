<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class workflow extends Model
{
    public function rules()  {
       return $this->hasMany(workflow_rule::class);
    }
    public function payments()
    {
        return $this->hasMany(payment::class);
    }
}
