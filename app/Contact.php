<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    protected $table = 'contacts';
    public $timestamps = true;
    protected $fillable = array('name', 'email', 'user_id','contact_type','message');

    public function Catogery()
    {
        return $this->belongsTo(Catogeryjob::class, 'catogeryjob_id')->withDefault();

    }//end of userMetas

}
