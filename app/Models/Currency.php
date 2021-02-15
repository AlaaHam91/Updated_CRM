<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Collection;

class Currency extends AppModel
{

    protected  $fillable = ['name','latin_name','code','active','logo','abbreviation'];

    public function units()
    {
        return $this->hasMany(CurrencyUnit::class);
    }

    public function rates()
    {
        return $this->hasMany(CurrencyRate::class);
    }

    public const CREATE_UPDATE_RULES = [
        'name'=>'required',
        'latin_name'=>'sometimes|string',
        'code'=>'required|max:15',
        'logo'=>'sometimes|image|mimes:jpeg,png,jpg,gif'
    ];
}
