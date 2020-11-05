<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sqits\UserStamps\Concerns\HasUserStamps;

class Catogery extends Model
{
    use Translatable;
    use HasUserStamps;
    use SoftDeletes;

    protected $fillable = ['icons'];
    protected $timestamp="false";
    public $translatedAttributes = ['name'];
}
