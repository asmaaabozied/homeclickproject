<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table="offers";

    protected $guarded = [];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withDefault();

    }//end of product

    public function catogery()
    {
        return $this->belongsTo(Catogery::class, 'catogery_id')->withDefault();

    }//end of catogery



    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id')->withDefault();

    }//end of store
}
