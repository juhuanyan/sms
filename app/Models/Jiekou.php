<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Jiekou
 */
class Jiekou extends Model
{
    protected $table = 'jiekou';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'url',
        'type',
        'datatype',
        'shoujihaobiaoshi',
        'neirongbiaoshi',
        'riqibiaoshi',
        'shijianbiaoshi',
        'fanhuizhibiaoshi',
        'chenggongdaima',
        'status'
    ];

    protected $guarded = [];

        
}