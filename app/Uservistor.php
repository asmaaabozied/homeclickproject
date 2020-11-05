<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Uservistor extends Model
{
    //uservistors

    protected $table="uservistors";
    protected $fillable = ['ip'];
}
