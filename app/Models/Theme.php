<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    public $timestamps = false;

    protected $table = 'tm_theme';

    protected $primaryKey = 'theme_id';

    protected $fillable = [
        'user_id',
        'title_apps',
        'title_header',
        'subtitle_header',
        'title_footer',
        'data_layout_mode',
        'data_topbar',
        'data_sidebar'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
