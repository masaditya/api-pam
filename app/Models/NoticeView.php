<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoticeView extends Model
{
    protected $table = 'notice_views';

    protected $fillable = [
        'company_id',
        'notice_id',
        'user_id',
        'read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function notice()
    {
        return $this->belongsTo(Notice::class, 'notice_id');
    }

}
