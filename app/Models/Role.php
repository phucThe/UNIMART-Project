<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table='roles';
    protected $guarded = ['id'];
    public $timestamps = false;

    function users(){
        return $this->belongsToMany('App\Models\User','user_role','role_id');
    }
}
