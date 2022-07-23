<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public static function slug_convert($string){
        return Str::slug($string);
    }

    public static function getFileURL($file_id){
        $base_url = "https://docs.google.com/uc?id=";
        return $file_url = $base_url.$file_id;
    }

    function product_colors(){
        return $this->belongsToMany('App\Models\Color','product_color','product_id');
    }

    function product_thumbs(){
        return $this->hasMany('App\Models\ProductThumb');
    }

    function product_category(){
        return $this->belongsTo('App\Models\ProductCat','product_cat_id');
    }

    function brand(){
        return $this->belongsTo('App\Models\ProductBrand','brand_id');
    }

    function orders(){
        return $this->belongsToMany('App\Models\Order','order_details','product_id');
    }
}
