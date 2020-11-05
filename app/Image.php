<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

    protected $table = 'images';
    public $timestamps = true;
    protected $fillable = array('imageable_id', 'imageable_type', 'image');

    protected $appends = ['image_path'];


    public function imageable()
    {
        return $this->morphTo();
    }

    public function getImagePathAttribute()
    {
        return asset('uploads/'.$this->image);

    }//end of get image path




}
