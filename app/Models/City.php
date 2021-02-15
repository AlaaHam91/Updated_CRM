<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends AppModel
{
    protected $fillable = ['name', 'latin_name', 'country_id'];

    public const CREATE_UPDATE_RULES = [
        'name'=>'required',
        'latin_name'=>'required',
        'country_id'=>'sometimes|numeric|exists:countries,id'
    ];

    public const MASS_CREATE_RULES = [
        'cities.*.name'=>'required',
        'cities.*.latin_name'=>'required',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
