<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'likes';
    private static $attributesQueue = [];
    protected $fillable = [
        'toUserId',
        'fromUserId',
        'itemId',
        'notifyId',
        'createAt',
    ];
}
