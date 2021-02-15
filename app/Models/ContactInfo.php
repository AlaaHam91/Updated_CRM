<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfo extends AppModel
{
    public function phones()
    {
        return $this->morphMany(CiPhone::class,'ciphonable','ciphonable_type','ciphonable_id');
    }

    public function mobiles()
    {
        return $this->morphMany(CiMobile::class,'cimobilable','cimobilable_type','cimobilable_id');
    }

    public function faxes(){
        return $this->morphMany(CiFax::class,'cifaxable','cifaxable_type','cifaxable_id');
    }

    public function emails()
    {
        return $this->morphMany(CiEmail::class,'ciemailable','ciemailable_type','ciemailable_id');
    }

    public function addresses(){
        return $this->morphMany(CiAddress::class,'ciaddressable','ciaddressable_type','ciaddressable_id');
    }

    public function positions(){
        return $this->morphMany(CiPosition::class,'cipositionable','cipositionable_type','cipositionable_id');
    }

    public function notes()
    {
        return $this->morphMany(CiNote::class,'cinotable','cinotable_type','cinotable_id');
    }


    public function comanyContactInfo(){
        return $this->belongsTo(CompanyContactInfo::class);
    }

}
