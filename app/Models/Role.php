<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;

    protected $table = 'users_role';

    protected $fillable = [
        'id',
        'role',
        'status'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
