<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CiMobile extends AppModel
{
    protected $fillable = ['communication_method_id','country_id','number'];

    protected $hidden = [
        'cimobilable_type','cimobilable_id','updated_at'
    ];

    protected $casts = [
        'created_at'=>'datetime:Y-m-d',
    ];

   /* public function rule(){
        return $this->hasOne(CmMobileConfiguration::class,'mobile_config_id','id');
    }*/

    public function socialNetworks()
    {
        return $this->belongsToMany(SocialNetwork::class,'ci_social_mobiles','ci_mobile_id','social_network_id');
    }


    public const  MOBILE_RULES = [
        "mobiles.*.communication_method_id" => "required",
        "mobiles.*.country_id" => "required|exists:countries,id",
    ];

    public function communicationMethod()
    {
        return $this->belongsTo(CommunicationMethod::class);
    }
}
