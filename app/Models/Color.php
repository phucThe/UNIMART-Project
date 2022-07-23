<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table='colors';

    function products(){
        return $this->belongsToMany('App\Models\Product','product_color','color_id');
    }
    function product_thumbs(){
        return $this->hasMany('App\Models\ProductThumb');
    }
}
