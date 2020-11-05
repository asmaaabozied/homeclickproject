<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'ratings';
    protected $guarded = [];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withDefault();

    }//end of product


    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id')->withDefault();

    }//end of product
}
