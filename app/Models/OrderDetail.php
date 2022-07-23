<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderDetail extends Model
{
    use HasFactory;
    protected $guarded=['id'];
    public $timestamps = false;
    protected $table='order_details';

    public static function slug_convert($string){
        return Str::slug($string);
    }
}
