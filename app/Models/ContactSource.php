<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSource extends AppModel
{
    protected $fillable = ['name','latin_name','type','parent_id'];

    public const CREATE_UPDATE_RULES = [
      'name'=>'required',
        'latin_name'=>'required',
        'type'=>'required'
    ];

    public function companies(){
        return $this->hasMany(Company::class,'contact_source_id','id');
    }
}
