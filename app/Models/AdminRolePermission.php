<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminRolePermission
 */
class AdminRolePermission extends Model
{
    protected $table = 'admin_role_permissions';

    public $timestamps = true;

    protected $fillable = [
        'role_id',
        'permission_id'
    ];

    protected $guarded = [];

        
}