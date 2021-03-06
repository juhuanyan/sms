<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Smss
 */
class Smss extends Model
{
    protected $table = 'smss';

    public $timestamps = true;

    protected $fillable = [
        'jiekouid',
        'caller',
        'msg',
        'deliverdate'
    ];

    protected $guarded = [];
}