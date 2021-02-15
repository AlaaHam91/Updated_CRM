<?php

namespace App\Models;



class SelectMenu extends AppModel
{
    protected $fillable = ['is_multi_selectable','type'];

    protected $hidden = ['created_at','updated_at'];

    protected $with = ['options'];

    public function additionalField()
    {
        return $this->morphOne(AdditionalField::class,'additionable');
    }

    public function options(){
        return $this->hasMany(SelectMenuOption::class);
    }
}
