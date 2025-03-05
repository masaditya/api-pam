<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveFile extends Model
{

    protected $table = 'leave_files';

    protected $fillable = [
        'company_id',
        'user_id',
        'leave_id',
        'filename',
        'hashname',
        'size',
        'added_by'
    ];

    public function leave()
    {
        return $this->belongsTo(Leave::class);
    }
}
