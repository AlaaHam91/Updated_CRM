<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends AppModel
{
    protected $fillable = ['name','latin_name'];

    public const CREATE_UPDATE_RULES = [
        'name'=>'required','latin_name'=>'required'
    ];
}
