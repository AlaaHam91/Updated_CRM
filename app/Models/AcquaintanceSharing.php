<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcquaintanceSharing extends AppModel
{
    //public $fillable = ['acquaintance_id','user_id'];

    public function users(){
       return $this->belongsToMany(User::class,'acquaintace_sharings');
    }
}
