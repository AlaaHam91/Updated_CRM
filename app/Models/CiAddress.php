<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CiAddress extends AppModel
{
    protected $fillable = ['communication_method_id','country_id','city_id'];

    protected $hidden = [
        'ciaddressable_type','ciaddressable_id','updated_at'
    ];
    protected $casts = [
        'created_at'=>'datetime:Y-m-d',
    ];

    public const CREATE_UPDATE_RULES =[
        'country_id'=>'required',
        'city_id'=>'required',
    ];

    public function communicationMethod()
    {
        return $this->belongsTo(CommunicationMethod::class);
    }
}
