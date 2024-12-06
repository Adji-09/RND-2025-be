<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public $timestamps = false;

    protected $table = 'tm_module';

    protected $primaryKey = 'module_id';

    protected $fillable = [
        'module_name',
        'module_icon',
        'module_url',
        'module_parent',
        'module_position',
        'module_status',
        'module_nav',
        'is_superadmin',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
