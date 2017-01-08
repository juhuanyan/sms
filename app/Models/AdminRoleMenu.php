<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminRoleMenu
 */
class AdminRoleMenu extends Model
{
    protected $table = 'admin_role_menu';

    public $timestamps = true;

    protected $fillable = [
        'role_id',
        'menu_id'
    ];

    protected $guarded = [];

        
}