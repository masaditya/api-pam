<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
    protected $fillable = ['company_name', 'app_name', 'company_email', 'company_phone', 'logo', 'light_logo'];
}
