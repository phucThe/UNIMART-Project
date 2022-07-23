<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostPostCat extends Model
{
    use HasFactory;

    protected $guarded=['id'];
    protected $table='post_post_cats';
    public $timestamps = false;
}
