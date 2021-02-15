<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneBook extends AppModel
{
    protected $fillable = ['company_person_id'];

    protected $hidden = ['updated_at'];

    protected $casts = [
        'created_at'=>'datetime:Y-m-d',
    ];

    public function personLife()
    {
        return $this->morphOne(CpersonLife::class,'personale', 'personale_type', 'personale_id');
    }

    public function companyPerson()
    {
        return $this->belongsTo(CompanyPerson::class);
    }
}
