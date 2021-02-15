<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends AppModel
{
    protected $cascadeDeletes = ['cities'];

    protected $fillable = ['name','latin_name','code'];

    public function cities()
    {
       return $this->hasMany(City::class);
    }

    public function jobs(){
        return $this->hasMany(Job::class);
    }

    public function adminDivisions()
    {
        return $this->hasMany(AdminDivision::class);
    }

    public const CREATE_UPDATE_RULES = [
        'name'=>'required',
        'latin_name'=>'required',
        'code'=>'required|max:5'
    ];


}
