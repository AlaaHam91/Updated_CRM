<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CiNote extends AppModel
{
    protected $fillable = ['communication_method_id','note','declaration'];
    protected $hidden = [
        'cinotable_type','cinotable_id','updated_at'
    ];
    protected $casts = [
        'created_at'=>'datetime:Y-m-d',
    ];

    public const  NOTE_RULES = [
        "notes.*.communication_method_id" => "required",
        "notes.*.note" => "required",
        "notes.*.declaration" => "required"
    ];

    public function communicationMethod()
    {
        return $this->belongsTo(CommunicationMethod::class);
    }
}
