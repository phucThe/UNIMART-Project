<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table='product_brands';

    function products(){
        return $this->hasMany('App\Models\Product','brand_id');
    }
}
