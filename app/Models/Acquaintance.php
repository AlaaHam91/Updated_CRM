<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Traits\HasContactInfoRelation;

class Acquaintance extends AppModel
{
  use HasContactInfoRelation;

   protected $with = ['phones','mobiles','faxes','addresses','positions','notes','emails'];
   protected $fillable = [
       'person_id',
       'created_by',
       'job_id',
       'job_place',
       'bank',
       'bank_account',
       'delegate_id',
       'commission_percentage',
       'is_deserving_commission'
   ];

   public const CREATE_UPDATE_RULES = [
       'acquaintance.job_id'=>'required',
       'acquaintance.created_by'=>'required',
       'acquaintance.job_place'=>'required',
       'acquaintance.delegate_id'=>'required'
   ];

   public function person(){
       return $this->belongsTo(Person::class);
   }

    public function sharedUsers(){
        return $this->belongsToMany(User::class,'acquaintance_sharings','acquaintance_id','user_id');
    }
}
