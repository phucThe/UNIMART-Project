<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded=['id'];

    public static function slug_convert($string){
        return Str::slug($string);
    }

    public static function getFileURL($file_id){
        $base_url = 'https://docs.google.com/uc?id=';
        return $file_url = $base_url.$file_id;
    }

    function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }

    function post_categories(){
        return $this->belongsToMany("App\Models\PostCat",'post_post_cats','post_id');
    }
}
