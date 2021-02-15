<?php

namespace App\Models;

class TextField extends AppModel
{
    protected $fillable = [
        'default_value',
        'max_length',
        'type'
    ];

    protected $hidden = ['created_at','updated_at'];

    public function additionalField()
    {
        return $this->morphOne(AdditionalField::class,'additionable');
    }
}
