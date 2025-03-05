<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyAddress extends Model
{
    protected $table = 'company_addresses';

    protected $fillable = ['country_id', 'address', 'is_default', 'location', 'tax_number', 'tax_name', 'longitude', 'latitude'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
