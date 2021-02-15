<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends AppModel
{
    protected $fillable = [
        'name','latin_name','job_type_id','salary','description','latin_description',
        'level','latin_level','work_nature','latin_work_nature','education_level_id',
        'skills','latin_skills','country_id','agents_available','confirm_request','education_id'
    ];

    public const CREATE_UPDATE_RULES = [
        'name'=>'required',
        'job_type_id'=>'required',
        'education_id'=>'required'
    ];
}
