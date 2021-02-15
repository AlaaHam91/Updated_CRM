<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpersonLifeActivity extends AppModel
{
    protected $fillable = ['cperson_life_id','activity_id'];
    protected $hidden = ['created_at','updated_at'];

    function personLife()
    {
        return $this->belongsTo(CpersonLife::class);
    }

    function activity()
    {
        return $this->belongsTo(Activity::class);
    }


}
