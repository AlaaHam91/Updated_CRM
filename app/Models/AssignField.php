<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignField extends Model
{
    protected $fillable = ['additional_field_id', 'entity_id'];

    public function fields()
    {
        return $this->belongsTo(AdditionalField::class);
    }
}
