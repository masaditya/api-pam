<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // Specify the table name (if it's not pluralized automatically)
    protected $table = 'attendances';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'company_id',
        'user_id',
        'location_id',
        'clock_in_time',
        'clock_out_time',
        'auto_clock_out',
        'clock_in_ip',
        'clock_out_ip',
        'working_from',
        'late',
        'half_day',
        'half_day_type',
        'added_by',
        'last_updated_by',
        'latitude',
        'longitude',
        'shift_start_time',
        'shift_end_time',
        'employee_shift_id',
        'work_from_type',
        'overwrite_attendance',
    ];

    // Define relationships (assuming the related models exist)

    // A user can have multiple attendance records
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // An attendance may be linked to a specific company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // An attendance may be linked to a specific location
    public function location()
    {
        return $this->belongsTo(CompanyAddress::class, 'location_id');
    }

    // Attendance may belong to a specific employee shift
    public function employeeShift()
    {
        return $this->belongsTo(EmployeeShift::class, 'employee_shift_id');
    }

    // Attendance may have been added by a specific user
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    // Attendance may have been updated by a specific user
    public function lastUpdatedBy()
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }
}