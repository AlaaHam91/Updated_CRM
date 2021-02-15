<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CiPosition extends AppModel
{
    protected $fillable = ['communication_method_id','longitude','latitude'];

    protected $hidden = [
        'cipositionable_type','cipositionable_id','updated_at'
    ];
    protected $casts = [
        'created_at'=>'datetime:Y-m-d',
    ];

    public const CREATE_UPDATE_RULES = [
        'coordinates'=>'required'
    ];

    public function communicationMethod()
    {
        return $this->belongsTo(CommunicationMethod::class);
    }
}
