<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminDivisionType extends AppModel
{
    protected $fillable = ['name','latin_name'];

    public function adminDivisions()
    {
        return $this->hasMany(AdminDivision::class,'type_id','id');
    }

    public const CREATE_UPDATE_RULES = [
        'name'=>'required',
        'latin_name'=>'sometimes|string|regex:/^[A-Za-z0-9\s]+$/',
    ];
}
