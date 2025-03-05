<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $table = 'designations';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'company_id',
        'name',
        'parent_id',
    ];
}
