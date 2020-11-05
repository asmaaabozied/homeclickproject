<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

use Sqits\UserStamps\Concerns\HasUserStamps;

class Category extends Model
{
    use Translatable;
    use HasUserStamps;
    use SoftDeletes;

    protected $guarded = [];
    public $translatedAttributes = ['name', 'description'];

    protected $hidden = [
        'created_by', 'updated_by', 'deleted_by', 'translations'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id')->withDefault();

    }//end of store
}//end of model
