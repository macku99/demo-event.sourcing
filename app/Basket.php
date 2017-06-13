<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string id
 * @property int    user_id
 */
class Basket extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'subtotal' => 'integer',
        'tax'      => 'integer',
        'total'    => 'integer',
        'products' => 'array',
    ];
}
