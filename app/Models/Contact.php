<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends AppModel
{
    protected $fillable = ['company_person_id'];
    //protected $with = ['companyPerson','cpersonLife'];

    protected $hidden = ['updated_at'];

    protected $casts = [
        'created_at'=>'datetime:Y-m-d',
    ];

    public function cpersonLife()
    {
        return $this->morphOne(CpersonLife::class,'personable');
    }

    public function companyPerson()
    {
        return $this->belongsTo(CompanyPerson::class);
    }
}
