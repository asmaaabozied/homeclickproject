<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table="adressuser";
    protected $guarded = [];



    protected $hidden=['deleted_at','created_by','updated_by','deleted_by'];
}
