<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalFieldsConfiguration extends AppModel
{
   protected $table = 'add_fields_configs';


    public function addressConfig()
    {
        return $this->morphOne(ContactConfiguration::class, 'configable');
    }

}
