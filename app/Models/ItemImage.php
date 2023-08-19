<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    protected $table = 'item_images';

    protected $fillable = [
        'item_id',
        'file_name',
        'status',
    ];

    protected $dates = ['uploaded_on'];

    public function getFileNameAttribute($value)
    {
        return !empty($value) ? url('/uploads/ad/' . $value) : '';
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
