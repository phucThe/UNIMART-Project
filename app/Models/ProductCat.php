<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductCat extends Model
{
    use HasFactory;

    protected $table='product_cats';
    protected $guarded = ['id'];
    public $timestamps = false;

    function products(){
        return $this->hasMany("App\Models\Product");
    }

    public static function slug_convert($string){
        return Str::slug($string);
    }

    public static function get_cat_tree($parent_id = 0, $level = 0)
    {
        $result = [];
        $product_categories = ProductCat::where('parent_id', $parent_id)->orderBy('name')->get();
        if (!empty($product_categories)) {
            foreach ($product_categories as $cat) {
                $cat->level = $level;
                $result[] = $cat;
                $child = ProductCat::get_cat_tree($cat->id, $level + 1);
                $result = array_merge($result, $child);
            }

            return $result;
        } else return false;
    }
}
