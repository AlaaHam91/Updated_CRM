<?php

namespace App\Models;

class NumberConfiguration extends AppModel
{
    protected $fillable = ['country_id','city_id','length','pattern','prefixes'];

    protected $hidden =['created_at','updated_at'];

    public function configuration()
    {
        return $this->morphOne(ContactConfiguration::class,"configurable");
    }
}
