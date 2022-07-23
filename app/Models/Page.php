<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Page extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public static function slug_convert($string){
        return Str::slug($string);
    }
}
