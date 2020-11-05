<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

use Sqits\UserStamps\Concerns\HasUserStamps;

class Product extends Model
{
    use Translatable;
    use HasUserStamps;
    use SoftDeletes;

    protected $guarded = [];
    protected $hidden = ['translations', 'created_by', 'updated_by', 'updated_at', 'deleted_at', 'deleted_by'];
    public $translatedAttributes = ['name', 'description'];

    protected $appends = ['image_path'];
    protected $with=['images','category'];


    public function getImagePathAttribute()
    {
        return asset('uploads/' . $this->image);

    }//end of get image path

    public function images()
    {

        return $this->morphMany(Image::class, 'imageable');

    }//end of images

    public function category()
    {
        return $this->belongsTo(Category::class, 'catogery_id')->withDefault();

    }//end of catogery


    public function offers()
    {
        return $this->hasMany(Offer::class, 'product_id');

    }//end of offers

    public function users()
    {
        return $this->belongsToMany(User::class);

    }//end of users


    public function ratings()
    {
        return $this->hasMany(Rating::class, 'product_id');

    }//end of ratings


    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')->withDefault();

    }//end of orders

}
