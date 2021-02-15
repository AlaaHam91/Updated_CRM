<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends AppModel
{
    protected $fillable = ['name','latin_name'];

    public const CREATE_RULES = [
        'name'=>'required|string',
        'latin_name'=>'sometimes|string',
    ];
}
