<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    //
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'payment_no',
        'vendor_id',
        'amount',
        'payment_date',
        'description',
        'workflow_id',
        'status',
        'current_step_no',
        'created_by',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function workflow()
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }
    
    public function approvals()
    {
        return $this->hasMany(PaymentApproval::class, 'payment_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
