<?php

namespace App;

use App\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\HasApiTokens;
use Modules\Geography\Entities\Geography;
use Sqits\UserStamps\Concerns\HasUserStamps;


class Store extends Authenticatable
{

    use LaratrustUserTrait;
    use HasApiTokens, Notifiable;
    use HasUserStamps;
    use SoftDeletes;

    protected $table = 'stores';
    public $timestamps = true;
    protected $guarded = [];
    protected $with=['images','catogery'];


    protected $hidden = [
        'created_by', 'updated_by', 'updated_at', 'deleted_at', 'deleted_by'
    ];

    protected $appends = ['image_path'];


    public function getImagePathAttribute()
    {
        return asset('uploads/' . $this->image);

    }//end of get image path

    public function users()
    {
        return $this->belongsToMany(User::class);

    }//end of users

    public function geogrphy(){

        return $this->belongsTo(Geography::class,'geography_id');

    }


    public function categories()
    {
        return $this->hasMany(Category::class);

    }//end of catogery


    public function catogery()
    {
        return $this->belongsTo(Catogery::class);

    }//end of catogery

    public function subsribe()
    {
        return $this->belongsTo(Subscribe::class, 'subscribe_id')->withDefault();

    }//end of subsribe

    public function images()
    {

        return $this->morphMany(Image::class, 'imageable');

    }//end of images

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'store_id');

    }//end of ratings


    protected $casts = [
        'description' => 'string',
        'status' => 'integer'
    ];
}
