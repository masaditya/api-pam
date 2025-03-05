<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patrol extends Model
{
    use HasFactory;

    protected $table = 'patrols';

    protected $fillable = [
        'name',
        'patrol_type_id',
        'image',
        'description',
        'longitude',
        'latitude',
        'added_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    
    public function patrolType()
    {
        return $this->belongsTo(PatrolTypes::class, 'patrol_type_id');
    }
}
