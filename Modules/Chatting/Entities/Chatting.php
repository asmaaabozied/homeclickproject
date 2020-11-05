<?php

namespace Modules\Chatting\Entities;

use Illuminate\Database\Eloquent\Model;

class Chatting extends Model
{
    protected $table ="chatting";
    protected $fillable = ['sender_id' ,'receiver_id' ,	'message', 'type' , 'created_at'];
}
