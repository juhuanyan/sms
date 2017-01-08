<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminPermission
 */
class AdminPermission extends Model
{
    protected $table = 'admin_permissions';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'slug'
    ];

    protected $guarded = [];

        
}