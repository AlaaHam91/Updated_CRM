<?php

namespace App\Models;

/**
 * Class Administration
 * @package App\Models
 */

class Administration extends AppModel
{
    protected $fillable = ['name','latin_name'];

    public const CREATE_RULES = [
        'name'=>'required|string',
        'latin_name'=>'sometimes|string',
    ];
}
