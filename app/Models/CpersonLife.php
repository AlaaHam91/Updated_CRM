<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpersonLife extends AppModel
{
    protected $fillable = ['user_id','job_id','job_title'];

    protected $with = ['companyPerson','lifeActivities','lifeInterests'];

    protected $hidden = ['updated_at','created_at','personable_type','personable_id'];

    public function personable()
    {
        return $this->morphTo();
    }

    public function companyPerson(){
          return $this->belongsTo(CompanyPerson::class);
    }
   /* public function agent()
    {
          return $this->morphMany(Agent::class,'agentable', 'agentable_type', 'agentable_id');
    }
    public function contact()
    {
          return $this->morphMany(Contact::class,'contactable', 'contactable_type', 'contactable_id');
    }
    public function phoneBook()
    {
      return $this->morphMany(PhoneBook::class,'phonebookable', 'phonebookable_type', 'phonebookable_id');
    }
    public function potentialAgent()
    {
          return $this->morphMany(PotentialAgent::class,'pagentable', 'pagentable_type', 'pagentable_id');
    }*/

    public function lifeActivities()
    {
        return $this->belongsToMany(Activity::class,'cperson_life_activities');
    }

    public function lifeInterests()
    {
      return $this->belongsToMany(Interest::class,'cperson_life_interests');
    }
}
