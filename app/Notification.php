<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


use Sqits\UserStamps\Concerns\HasUserStamps;

class Notification extends Model
{
    use HasUserStamps;
    use SoftDeletes;

    protected $table = "notifications";

    protected $guarded = [];


    protected $hidden = [
        'created_by', 'updated_by', 'deleted_at', 'deleted_by', 'translations'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');

    }//end of user


}
