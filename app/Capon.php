<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Capon extends Model
{
    protected $table = "capons";

    protected $guarded = [];

    protected $hidden = [
        'created_by', 'updated_by', 'updated_at', 'deleted_at', 'deleted_by'
    ];


    public function orders()
    {
        return $this->hasMany(Order::class, 'capon_id');

    }//end of offers

}
