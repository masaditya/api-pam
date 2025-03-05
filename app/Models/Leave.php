<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{

    // Specify the table name (if it's not pluralized automatically)
    protected $table = 'leaves';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'company_id',
        'user_id',
        'leave_type_id',
        'unique_id',
        'duration',
        'leave_date',
        'reason',
        'status',
        'reject_reason',
        'paid',
        'added_by',
        'last_updated_by',
        'event_id',
        'approved_by',
        'approved_at',
        'half_day_type',
        'manager_status_permission',
        'approve_reason',
        'over_utilized',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }
}
