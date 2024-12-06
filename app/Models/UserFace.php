<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFace extends Model
{
    public $timestamps = false;

    protected $table = 'users_face';

    protected $fillable = [
        'user_id',
        'image_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
