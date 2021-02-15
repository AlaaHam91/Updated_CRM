<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialNetwork extends AppModel
{
    protected $fillable = ['name','latin_name'];

    public const CREATE_UPDATE_RULES = [
        'name'=>'required','latin_name'=>'required'
    ];

    public function contactEmails()
    {
        return $this->belongsToMany(CiEmail::class,'ci_social_emails','ci_email_id','social_network_id');
    }

    public function contactMobiles()
    {
        return $this->belongsToMany(CiMobile::class,'ci_social_mobiles','ci_mobile_id','social_network_id');
    }
}
