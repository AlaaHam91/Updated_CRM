<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interest extends AppModel
{
    protected $fillable = ['name','latin_name'];

    protected $hidden = ['updated_at','created_at','pivot'];

    public const CREATE_UPDATE_RULES = [
        'name'=>'required',
        'latin_name'=>'required'
    ];

    public function companies(){
        return $this->belongsToMany(Company::class,'company_interests','company_id','interest_id');
    }

    public function peopleInterests()
    {
        return $this->belongsToMany(CpersonLife::class,'cperson_life_interests');
    }
}
