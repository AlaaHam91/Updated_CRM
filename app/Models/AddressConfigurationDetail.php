<?php

namespace App\Models;


class AddressConfigurationDetail extends AppModel
{
    protected $fillable = ['adr_config_id','label','latin_label','is_required'];

    public function addressConfiguration()
    {
        return $this->belongsTo(AddressConfiguration::class,'adr_config_id');
    }

    const CREATE_UPDATE_RULES = [
        'address.details.*.label'=>'required',
        'address.details.*.latin_label'=>'required',
    ];
}
