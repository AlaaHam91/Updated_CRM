<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CiAddressDetail extends AppModel
{
    protected $fillable = ['ci_address_id','value','latin_value'];

    public const  PHONE_RULES = [
        "details.*.ci_address_id" => "sometimes",
        "details.*.value" => "required",
        "details.*.latin_value" => "required"
    ];
}
