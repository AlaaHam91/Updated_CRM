<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyUnit extends AppModel
{
    protected $table = 'currency_units';

    protected  $fillable = ['currency_id','name','latin_name','unit_value'];

    public function currency(){
        return $this->belongsTo(Currency::class);
    }

    public const CREATE_UPDATE_RULES = [
        'currency.*.name'=>'required',
        'currency.*.latin_name'=>'sometimes|string',
        'currency.*.unit_value'=>'required|numeric'
    ];
}
