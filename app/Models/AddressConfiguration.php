<?php

namespace App\Models;


class AddressConfiguration extends AppModel
{
    protected $fillable = ['country_id','city_id','fields_count'];

    protected $with = ['addressConfigurationDetails'];

    protected  $hidden = ["created_at", "updated_at"];

    public function configuration()
    {
        return $this->morphOne(ContactConfiguration::class,"configurable");
    }

    public function addressConfigurationDetails()
    {
        return $this->hasMany(AddressConfigurationDetail::class,'adr_config_id');
    }

    const CREATE_UPDATE_RULES = [
        'address.country_id'=>'required',
        'address.city_id'=>'required',
        'address.fields_count'=>'required|numeric|min:1|max:4',
    ];
}
