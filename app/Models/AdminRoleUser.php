<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminRoleUser
 */
class AdminRoleUser extends Model
{
    protected $table = 'admin_role_users';

    public $timestamps = true;

    protected $fillable = [
        'role_id',
        'user_id'
    ];

    protected $guarded = [];

        
}