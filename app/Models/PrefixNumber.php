<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrefixNumber extends AppModel
{
    public function number(){
        return $this->belongsTo(NumberConfiguration::class);
    }
}
