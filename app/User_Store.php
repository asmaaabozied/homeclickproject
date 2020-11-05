<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_Store extends Model
{
    protected $table = 'store_user';
    protected $fillable = ['user_id','store_id'];
    public $timestamps = false;




}
