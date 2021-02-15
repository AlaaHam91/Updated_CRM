<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends AppModel
{
    protected $fillable = ['name','latin_name'];

    protected $hidden = ['updated_at','created_at','pivot'];


    public function companies(){
        return $this->belongsToMany(Company::class,'company_activities','company_id','activity_id');
    }

    public function peopleLives()
    {
        return $this->belongsToMany(CpersonLife::class,'cperson_life_activities');
    }

    public const CREATE_UPDATE_RULES = [
        'name'=>'required',
        'latin_name'=>'required'
    ];

}
