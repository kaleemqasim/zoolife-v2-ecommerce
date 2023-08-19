<?php

namespace App\Models;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ItemComment extends Model
{
    protected $table = 'item_comments';
    private static $attributesQueue = [];
    protected $fillable = [
        'itemId',
        'userId',
        'message'
    ];
    const CREATED_AT = 'co';
    const UPDATED_AT = 'uo';

    protected $dates = [
        'co',
        'uo',
    ];

    protected $appends = [
        'user_name',
    ];
    public function items()
    {
        return $this->belongsToMany(Item::class, 'itemId', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
    
    public function getUserNameAttribute()
    {
        return $this->user->username;
    }
}
