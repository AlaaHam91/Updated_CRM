<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends AppModel
{
    protected $table = "persons";
    protected $fillable = ['name','latin_name','image','birth_date'];

    protected $hidden = ['created_at','updated_at'];

    public const CREATE_UPDATE_RULES = [
        'name'=>'required','latin_name'=>'required'
    ];

    public function acquaintance(){
        return $this->hasOne(Acquaintance::class);
    }

    public function companyPerson(){
        return $this->hasOne(CompanyPerson::class);
    }
}
