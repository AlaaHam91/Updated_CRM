<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CiFax extends AppModel
{
    protected $fillable = ['communication_method_id','country_id','city_id','number','transfer'];
    protected $hidden = [
        'cifaxable_type','cifaxable_id','updated_at'
    ];
    protected $casts = [
        'created_at'=>'datetime:Y-m-d',
    ];

    public const  FAX_RULES = [
        "faxes.*.communication_method_id" => "required",
        "faxes.*.country_id" => "required|exists:countries,id",
    ];

    public function communicationMethod()
    {
        return $this->belongsTo(CommunicationMethod::class);
    }
}
