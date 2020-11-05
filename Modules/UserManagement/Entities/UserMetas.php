<?php

namespace Modules\UserManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class UserMetas extends Model
{
    protected $fillable = ['user_id','attr_key','attr_value'];

    
}
