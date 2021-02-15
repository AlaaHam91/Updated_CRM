<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentContract extends AppModel
{

    protected $fillable = [
        'agent_id','number','start_date',
        'end_date','internal_visits',
        'external_visits','support_hours',
        'training_hours'
    ];

    public function agent(){
        return $this->belongsTo(Agent::class);
    }
}
