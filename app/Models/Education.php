<?php

namespace App\Models;


class Education extends AppModel
{
    protected $fillable = ['name','latin_name'];

    public const CREATE_UPDATE_RULES = [
        'name'=>'required',
        'latin_name'=>'required'
    ];
}
