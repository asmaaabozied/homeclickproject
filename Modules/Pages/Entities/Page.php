<?php

namespace Modules\Pages\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Stadiums\Entities\Scopes\ActiveScope;
use Sqits\UserStamps\Concerns\HasUserStamps;

class Page extends Model
{
    use Translatable;


    protected $table = 'pages';
    protected $guarded = [];
    protected $fillable = ['slug','active'];
    public $translatedAttributes =['name','title','content'];
 //   protected static function boot()
   // {
      //  parent::boot();
      //  static::addGlobalScope(new ActiveScope());

   //--------- }
}
