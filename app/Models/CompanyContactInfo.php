<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyContactInfo extends AppModel
{
     protected $fillable = ['company_id','contact_info_id'];

     public function company (){
       return $this->belongsTo(Company::class);
     }

   public function contacts(){
       return $this->hasOne(ContactInfo::class);
   }
}
