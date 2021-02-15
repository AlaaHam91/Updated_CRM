<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CiEmail extends AppModel
{
    protected $fillable = ['communication_method_id','email'];
    protected $hidden = [
        'ciemailable_type','ciemailable_id','updated_at'
    ];
    protected $casts = [
        'created_at'=>'datetime:Y-m-d',
    ];

    public function socialNetworks()
    {
        return $this->belongsToMany(SocialNetwork::class,'ci_social_emails','ci_email_id','social_network_id');
    }

    public const  EMAIL_RULES = [
        "emails.*.communication_method_id" => "required",
        "emails.*.email" => "required|email"
    ];

    public function communicationMethod()
    {
        return $this->belongsTo(CommunicationMethod::class);
    }
}
