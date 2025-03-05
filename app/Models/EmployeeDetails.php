<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDetails extends Model
{
    protected $table = 'employee_details';
    protected $fillable = ['company_id', 'user_id', 'company_address_id', 'designation_id', 'department_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyAddress::class, 'company_address_id');
    }

    public function companies()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    
    public function team()
    {
        return $this->belongsTo(Team::class, 'department_id');
    }
}
