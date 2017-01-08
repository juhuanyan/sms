<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminMenu
 */
class AdminMenu extends Model
{
    protected $table = 'admin_menu';

    public $timestamps = true;

    protected $fillable = [
        'parent_id',
        'order',
        'title',
        'icon',
        'uri'
    ];

    protected $guarded = [];

        
}