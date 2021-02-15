<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends AppModel
{
    protected $fillable = ['related_id','rate'];

    public const CREATE_UPDATE_RULES = [
        'rates.*.related_id'=>'required|exists:currencies,id',
        'rates.*.rate'=>'required|numeric'
    ];

    public function currency(){
        return $this->belongsTo(Currency::class);
    }
}
