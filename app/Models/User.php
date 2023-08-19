<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class User extends Model
{
    protected $table = 'users';
    private static $attributesQueue = [];
    protected $fillable = [
        'name',
        'disclaimer',
        'phone_country_code',
        'city_id',
        'gender',
        'login',
        'email',
        'status',
        'otp',
        'phone',
        'verify',
        'username',
        'password',
        'device_token',
        'device_type',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'msg_badge',
        'noti_badge'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleted(function($user)
        {
            $user->items()->delete();
        });
    }

    public function items()
    {
        return $this->hasMany(Item::class,'fromUserId', 'id');
    }
    public function itemFavourites()
    {
        return $this->hasMany(ItemFavourite::class,'fromUserId', 'id');
    }
}
