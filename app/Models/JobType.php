<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobType extends AppModel
{
    protected $fillable = ['name','latin_name'];

    public const CREATE_UPDATE_RULES = [
        'name'=>'required',
        'latin_name'=>'required'
    ];

    public function jobs(){
        return $this->hasMany(Job::class,'job_type_id','id');
    }
}
