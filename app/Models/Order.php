<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table= 'orders';
    protected $guarded = ['id'];

    function products(){
        return $this->belongsToMany('App\Models\Product','order_details','order_id');
    }

    function shipping(){
        return $this->hasOne('App\Models\Shipping','id');
    }
}
