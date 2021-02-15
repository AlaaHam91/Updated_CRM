<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends AppModel
{
    public const CREATE_UPDATE_RULES = [
        'name' => 'required',
        'latin_name' => 'required',
        'code' => 'sometimes|string',
        'company_id' => 'required',
        'employee_id' => 'required',
        'branch_id' => 'required',
        'department_id' => 'required',
        'country_id' => 'required',
        'longitude' => 'sometimes|numeric',
        'latitude' => 'sometimes|numeric',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'latin_name',
        'code',
        'company_id',
        'employee_id',
        'branch_id',
        'department_id',
        'country_id',
        'longitude',
        'latitude',
    ];
}
