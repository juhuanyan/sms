<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminUser
 */
class AdminUser extends Model
{
    protected $table = 'admin_users';

    public $timestamps = true;

    protected $fillable = [
        'username',
        'password',
        'name',
        'remember_token',
        'status',
        'jiekouid',
        'jiangeshijian',
        'fangwenip',
        'dengluip',
        'jiekouurl'
    ];

    protected $guarded = [];

        
}