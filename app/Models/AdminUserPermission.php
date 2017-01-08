<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminUserPermission
 */
class AdminUserPermission extends Model
{
    protected $table = 'admin_user_permissions';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'permission_id'
    ];

    protected $guarded = [];

        
}