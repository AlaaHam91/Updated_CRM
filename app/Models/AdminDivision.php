<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminDivision extends AppModel
{
    /**
     * @var string[]
     */
    protected $fillable = ['id','name','latin_name','type_id','code','country_id','parent_id','is_active'];
    /**
     * @var array
     */
    protected $hidden = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Type(){
        return $this->belongsTo(AdminDivisionType::class,'type_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function agents()
    {
        return $this->belongsToMany(Agent::class,'agent_admin_divs');
    }

    /**
     * validation rules for the model creation.
     */
    public const CREATE_UPDATE_RULES = [
        'name'=>'required',
        'latin_name'=>'sometimes|string|regex:/(^[A-Za-z0-9\s\-\*\_\.\+]+)$/',
        'type_id'=>'required|numeric',
        'country_id'=>'required|numeric',
        'parent_id'=>'sometimes|exists:admin_divisions',
        'is_active'=>'sometimes|numeric'
    ];
}
