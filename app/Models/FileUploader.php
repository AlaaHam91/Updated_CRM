<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileUploader extends AppModel
{
    protected $fillable =['multi_files','type'];

    public function additionalField()
    {
        return $this->morphOne(AdditionalField::class, 'additionable');
    }

}
