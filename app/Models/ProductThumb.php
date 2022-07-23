<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductThumb extends Model
{

    use HasFactory;
    use SoftDeletes;

    protected $table = 'product_thumbs';

    protected $guarded = ['id'];

    function product(){
        $this->belongsTo('App\Models\Product','product_id');
    }
    function color(){
        $this->belongsTo('App\Models\Color','color_id');
    }

}
