<?php

namespace BrosSquad\Linker\Api\Models;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    protected $fillable = [
        'name',
        'key',
    ];

    protected $hidden = ['key'];
}
