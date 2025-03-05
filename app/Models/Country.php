<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';
    protected $fillable = ['iso', 'name', 'nicename', 'iso3', 'numcode', 'phonecode'];
}
