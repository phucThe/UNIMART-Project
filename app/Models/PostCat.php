<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PostCat extends Model
{
    use HasFactory;

    protected $table='post_cats';
    protected $guarded = ['id'];
    public $timestamps = false;
    function posts(){
        return $this->belongsToMany("App\Models\Post",'post_post_cats','post_cat_id');
    }

    public static function slug_convert($string){
        return Str::slug($string);
    }

    public static function get_cat_tree($parent_id = 0, $level = 0)
    {
        $result = [];
        $post_categories = PostCat::where('parent_id', $parent_id)->orderBy('name')->get();
        if (!empty($post_categories)) {
            foreach ($post_categories as $cat) {
                $cat->level = $level;
                $result[] = $cat;
                $child = PostCat::get_cat_tree($cat->id, $level + 1);
                $result = array_merge($result, $child);
            }

            return $result;
        } else return false;
    }
}
