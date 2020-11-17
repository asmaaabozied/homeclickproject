<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";

    protected $guarded = [];


    protected $hidden = ['translations', 'created_by', 'updated_by', 'updated_at', 'deleted_at', 'deleted_by'];



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();

    }//end of user


    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id')->withDefault();

    }//end of address

    public function copan()
    {
        return $this->belongsTo(Capon::class, 'capon_id');

    }//end of coupan


    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id')->withDefault();

    }//end of store


    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')->withPivot('quantity');

    }//end of products
}
