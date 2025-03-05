<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoticeFile extends Model
{
    protected $table = 'notice_files';

    protected $fillable = [
        'notice_id',
        'filename',
        'google_url',
        'hashname',
        'external_link',
        'dropbox_link',
        'external_link_name',
    ];

    public function notice()
    {
        return $this->belongsTo(Notice::class, 'notice_id');
    }
}
