<?php

namespace App\Models;

use Traits\HasContactInfoRelation;

class Company extends AppModel
{
    use HasContactInfoRelation;

    protected $fillable = ['name','latin_name','logo','type','parent_id','contact_source_id',
        'is_visitor','user_id','acquaintance_method_id','preferred_language_id',
        'acquaintance_id','number_of_employees','is_active'
    ];

    public function acquaintanceMethod(){
        return $this->belongsTo(AcquaintanceMethod::class,'acquaintance_method_id','id');
    }

    public function contactSource(){
        return $this->belongsTo(ContactSource::class,'contact_source_id','id');
    }

    public function activities(){
        return $this->belongsToMany(Activity::class,'company_activities','company_id','activity_id');
    }

    public function interests(){
        return $this->belongsToMany(Interest::class,'company_interests','company_id','interest_id');
    }

    public function companyPeople(){
        return $this->hasMany(CompanyPerson::class);
    }


    public function contactsInfo(){
        return $this->hasMany(CompanyContactInfo::class);
    }

    public const CREATE_RULES = [
        'type'=>'required',
        'name'=>'required',
        'latin_name'=>'required',
        'acquaintance_method_id'=>'required',
        'number_of_employees'=>'required',
        'activities'=>'required',
        'interests'=>'required'
    ];

    public const UPDATE_RULES = [
        'type'=>'required',
        'name'=>'required',
        'latin_name'=>'required',
        'acquaintance_method_id'=>'required',
        'number_of_employees'=>'required',
        'activities'=>'required',
        'interests'=>'required'
    ];


    public function sharedUsers(){
        return $this->belongsToMany(User::class,'company_sharings','company_id','user_id');
    }

    public function files(){
        return $this->belongsToMany(File::class,'company_files','company_id','file_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fields()
    {
        return $this->belongsToMany(AdditionalField::class,"company_additionals")->withPivot("value");
    }

}
