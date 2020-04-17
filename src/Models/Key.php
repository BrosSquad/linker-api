<?php

namespace BrosSquad\Linker\Api\Models;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'key',
    ];

    /**
     * @var array
     */
    protected $hidden = ['key'];
}
