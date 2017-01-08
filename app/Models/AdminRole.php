<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminRole
 */
class AdminRole extends Model
{
    protected $table = 'admin_roles';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'slug'
    ];

    protected $guarded = [];

        
}