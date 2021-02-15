<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactConfiguration extends Model
{
    protected $fillable = ['communication_method_id'];

    protected $hidden =['created_at','updated_at','configurable_type','configurable_id'];

    protected $with = ['configurable'];

    public function configurable()
    {
        return $this->morphTo();
    }

    function communicationMethod()
    {
        return $this->belongsTo(CommunicationMethod::class);
    }
}
