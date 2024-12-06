<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public $timestamps = false;

    protected $table = 't_log_activity';

    protected $primaryKey = 'id_log';

    protected $fillable = [
        'id_user',
        'activity',
        'browser',
        'platform',
        'ip_address'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
