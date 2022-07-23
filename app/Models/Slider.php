<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'sliders';

    public static function getFileURL($file_id){
        $base_url = 'https://docs.google.com/uc?id=';
        return $file_url = $base_url.$file_id;
    }
}
