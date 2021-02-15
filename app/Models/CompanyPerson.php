<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Traits\HasContactInfoRelation;

class CompanyPerson extends AppModel
{
    use HasContactInfoRelation;

    protected $fillable = [
        'company_id',
        'person_id',
        'is_active',
        'is_responsible',
        'contact_source_id',
        'acquaintance_method_id',
        'acquaintance_id',
        'language_id',
        'user_id',
        'is_removed'
    ];
    protected $with = ['phones','mobiles','faxes','positions',
    'emails','notes','person',
    'contact.cpersonLife',
    'phoneBook.cpersonLife',
    'agent.cpersonLife',
    'potentialAgent.cpersonLife'
   ];

   protected $hidden = ['created_at','updated_at'];
   // protected $with = ['personLife'];
    public const CREATE_UPDATE_RULES = [
        'cperson.company_id'=>'required',
        'cperson.language_id'=>'required',
    ];

    public function person(){
        return $this->belongsTo(Person::class);
    }

    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function sharedTo()
    {
          return $this->belongsToMany(User::class,'cperson_sharings','company_person_id','user_id');
    }

    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function personLife()
    {
       return $this->hasMany(CPersonLife::class);
    }

    public function contact(){
        return $this->hasOne(Contact::class);
    }

    public function phoneBook(){
        return $this->hasOne(PhoneBook::class);
    }

    public function potentialAgent()
    {
        return $this->hasOne(PotentialAgent::class);
    }

    public function agent()
    {
        return $this->hasOne(Agent::class);
    }
}
