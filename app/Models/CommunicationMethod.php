<?php

namespace App\Models;


class CommunicationMethod extends AppModel
{
    protected $fillable = ["name",'latin_name','is_required','is_default','type'];

    protected $hidden =['created_at','updated_at'];
    
    function contactConfiguration()
    {
        return $this->hasMany(ContactConfiguration::class);
    }

    const CREATE_UPDATE_RULES = [
        'name'=>'required',
        'latin_name'=>'required',
        'type'=>'required',
        'is_required'=>'sometimes|boolean',
        'is_default'=>'sometimes|boolean',
    ];

    const MOBILE_RULES = [
        'mobile.*.country_id'=>'required',
        'mobile.*.length'=>'required',
        'mobile.*.pattern'=>'sometimes|string',
        'mobile.*.prefixes'=>'sometimes|string',
    ];

    const PHONE_RULES = [
        'phone.*.country_id'=>'required',
        'phone.*.city_id'=>'required',
        'phone.*.length'=>'required',
        'phone.*.pattern'=>'sometimes|string',
        'phone.*.prefixes'=>'sometimes|string',
    ];

    const FAX_RULES = [
        'fax.*.country_id'=>'required',
        'fax.*.city_id'=>'required',
        'fax.*.length'=>'required',
        'fax.*.pattern'=>'sometimes|string',
        'fax.*.prefixes'=>'sometimes|string',
    ];
}
