<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcquaintanceMethod extends AppModel
{
    protected $fillable = ['name','latin_name'];

    public const CREATE_UPDATE_RULES = [
        'name'=>'required','latin_name'=>'required'
    ];

    public function companies(){
        return $this->hasMany(Company::class,'acquaintance_method_id','id');
    }
}
