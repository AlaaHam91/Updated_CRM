<?php

namespace App\Models;
class File extends AppModel
{
    protected $fillable=['path'];

    public const CREATE_UPDATE_RULES =[

    ];

    public function companies(){
        return $this->belongsToMany(Company::class,'company_files','company_id','file_id');
    }
}
