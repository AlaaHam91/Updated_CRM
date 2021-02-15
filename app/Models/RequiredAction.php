<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequiredAction extends AppModel
{
    protected $fillable = [
            'name',
            'latin_name',
            'default_time',
            'close_type',
            'required_delivery_date',
            'progress_rate',
            'implementation_no',
            'finish_type',
            'confirm_project',
            'variable_field',
            'correct_value',
    ];
}
