<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatrolTypes extends Model
{
    protected $table = 'patrol_types';

    protected $fillable = [
        'name', 'company_id'
    ];
}
