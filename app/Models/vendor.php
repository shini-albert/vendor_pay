<?php

namespace App\Models;
use App\Http\Controllers\PaymentController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';

    protected $fillable = [
        'name',
        'vendor_type',
        'is_active',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'vendor_id');
    }
}
