<?php

namespace Modules\Geography\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;


class Geography extends Model
{
    use Translatable;
    public $translatedAttributes = ['name'];

    protected $fillable = ['name','parent_id'];
    protected $hidden = [
        'created_at', 'updated_at',
    ];




    public $with=['cities'];

    public function Cities(){

        return $this->hasMany(Geography::class,'parent_id')->select('id' , 'parent_id');
    }

    public function users(){

        return $this->hasMany(User::class,'user_id');
    }



}
