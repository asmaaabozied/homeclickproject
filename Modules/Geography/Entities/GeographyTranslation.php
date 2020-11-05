<?php

namespace Modules\Geography\Entities;

use Illuminate\Database\Eloquent\Model;

class GeographyTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
}
