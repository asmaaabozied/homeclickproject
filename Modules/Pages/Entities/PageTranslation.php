<?php

namespace Modules\Pages\Entities;

use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
    public $timestamps = false;
    protected $fillable =['name','title','content'];
}
