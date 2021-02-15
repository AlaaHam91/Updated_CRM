<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CiPhone extends AppModel
{
    protected $fillable = ['communication_method_id','country_id','city_id','number','transfer_number'];

    protected $hidden = [
        'ciphonable_type','ciphonable_id','updated_at'
    ];
    protected $casts = [
        'created_at'=>'datetime:Y-m-d',
    ];

    public const  PHONE_RULES = [
        "phones.*.communication_method_id" => "required",
        "phones.*.country_id" => "required|exists:countries,id",
    ];

    public function communicationMethod()
    {
        return $this->belongsTo(CommunicationMethod::class);
    }
}
