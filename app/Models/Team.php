<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'teams';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'company_id',
        'team_name',
    ];
}
