<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpersonLifeInterest extends AppModel
{
    protected $fillable = ['cperson_life_id','interest_id'];
    protected $hidden = ['created_at','updated_at'];
    function personLife()
    {
        return $this->belongsTo(CpersonLife::class);
    }

    function interest()
    {
        return $this->belongsTo(Interest::class);
    }
}
