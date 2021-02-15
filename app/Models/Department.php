<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends AppModel
{
    protected $fillable = ['name','latin_name','type','parent_id','status'];

    public const CREATE_RULES = [
        'name'=>'required|string',
        'latin_name'=>'sometimes|string',
        'type'=>'required|in:main,branch',
        'parent_id'=>'sometimes|required|numeric',
        'status'=>'required|in:internal,external'
    ];
}
