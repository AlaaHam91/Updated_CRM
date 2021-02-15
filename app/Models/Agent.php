<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends AppModel
{
    protected $fillable = ['company_person_id','code','account_number','user_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function personLife()
    {
        return $this->morphOne(CpersonLife::class,'personale', 'personale_type', 'personale_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companyPerson()
    {
        return $this->belongsTo(CompanyPerson::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function adminDivs()
    {
        return $this->belongsToMany(AdminDivision::class,'agent_admin_divs');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class,'agent_products');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contracts(){
        return $this->hasMany(AgentContract::class);
    }

    protected $hidden = ['updated_at'];

    protected $casts = [
        'created_at'=>'datetime:Y-m-d',
    ];
}
