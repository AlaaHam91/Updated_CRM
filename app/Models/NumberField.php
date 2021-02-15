<?php

namespace App\Models;



class NumberField extends AppModel
{
    protected $fillable = [
        'default_value',
         'max',
         'min',
         'step',
         'type'
     ];

     protected $hidden = ['created_at','updated_at'];

    public function additionalField()
    {
        return $this->morphOne(AdditionalField::class,'additionable');
    }

}
