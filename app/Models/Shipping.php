<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDO;

class Shipping extends Model
{
    use HasFactory;

    protected $table= 'shipping';
    protected $guarded = ['id'];

    function orders(){
        return $this->belongsTo('App\Models\Order','id');
    }
}
