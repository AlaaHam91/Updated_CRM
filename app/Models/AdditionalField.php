<?php

namespace App\Models;


class AdditionalField extends AppModel
{
    protected $fillable = ['name',
                           'latin_name',
                           'html_name',
                           'html_id',
                           'html_class',
                           'is_required',
                           'additionable_id',
                           'additionable_type'
    ];
    protected $hidden = [
        'created_at','updated_at','additionable_id','additionable_type'
    ];

    protected $with = ['additionable', 'assignments'];
    public function additionable()
    {
        return $this->morphTo();
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class,"company_additionals")->withPivot("value");
    }

    public function assignments()
    {
        return $this->hasMany(AssignField::class);
    }
}
