<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteStat extends Model
{
    protected $table = 'site_stats';

    protected $fillable = [
        'key',
        'label',
        'value',
        'suffix',
        'sort_order',
    ];
}
