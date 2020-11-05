<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = "carts";

    protected $guarded = [];

    protected $hidden = [
        'created_by', 'updated_by', 'updated_at', 'deleted_at', 'deleted_by'
    ];

}
