<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminOperationLog
 */
class AdminOperationLog extends Model
{
    protected $table = 'admin_operation_log';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'path',
        'method',
        'ip',
        'input'
    ];

    protected $guarded = [];

        
}