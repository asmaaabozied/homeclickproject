<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

use Sqits\UserStamps\Concerns\HasUserStamps;

class Subscribe extends Model
{
    use Translatable;
    use HasUserStamps;
    use SoftDeletes;


    protected $guarded = [];
    public $translatedAttributes = ['name', 'description'];

    protected $hidden = ['translations' ];


    public function stores()
    {
        return $this->hasMany(Store::class,'subscribe_id');

    }//end of stores

}
