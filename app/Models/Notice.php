<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $table = 'notices';

    protected $fillable = [
        'company_id',
        'to',
        'heading',
        'description',
        'department_id',
        'added_by',
        'last_updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function member()
    {
        return $this->hasMany(NoticeView::class, 'notice_id');
    }

    public function department()
    {
        return $this->belongsTo(Team::class, 'department_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(NoticeFile::class, 'notice_id');
    }
}
