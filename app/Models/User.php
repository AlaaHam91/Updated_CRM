<?php

namespace App\Models;

use App\Models\Company;
use Helpers\Constants;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Traits\HasContactInfoRelation;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens,HasRoles,HasContactInfoRelation;

    /**
     * Validation Rules for creation and updating.
     */
    public const CREATE_UPDATE_RULES = [
        'name'=>'required',
        'latin_name'=>'required',
        'is_active'=>'sometimes|boolean',
        'account_number'=>'sometimes|string',
        'identity_number'=>'sometimes|string',
        'administration_id'=>'required|numeric',
        'branch_id'=>'required|numeric',
        'department_id'=>'required|numeric',
        'manager_id'=>'sometimes|required|numeric',
        'job_id'=>'required|numeric',
        'job_title'=>'sometimes|string',
        'tickets_email'=>'sometimes|email|unique:users',
        'notes'=>'sometimes|string',
        'hierarchy_id'=>'required',
        'user_name'=>'required',
        'password'=>'required',
        'email'=>'required|email|unique:users',
        'managerial_level_id'=>'sometimes|required|numeric',
        'image'=>'sometimes|mimes:jpg,jpeg,png,bmp,gif'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'latin_name',
        'is_active',
        'account_number',
        'identity_number',
        'administration_id',
        'branch_id',
        'department_id',
        'manager_id',
        'job_id',
        'job_title',
        'tickets_email',
        'notes',
        'hierarchy_id',
        'user_name',
        'password',
        'email',
        'managerial_level_id',
        'image'

    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Company(){
        return $this->belongsToMany(Company::class,'company_sharings','company_id','user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sharedCPerson()
    {
        return $this->belongsToMany(CompanyPerson::class,'cperson_sharings','company_person_id','user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sharedAcquaintance(){
        return $this->belongsToMany(Acquaintance::class,'acquaintance_sharings','acquaintance_id','user_id');
    }

    /**
     * @param $builder
     * @param null $filters
     * @param string $filterOperator
     * @return mixed
     */
    public function scopeFilter($builder, $filters = null,$filterOperator = "=")
    {
        if (isset($filters) && is_array($filters)) {
            foreach ($filters as $field => $value) {
                if ($value == Constants::NULL)
                    $builder->whereNull($field);
                elseif ($value == Constants::NOT_NULL)
                    $builder->whereNotNull($field);
                elseif (is_array($value))
                    $builder->whereIn($field, $value);
                elseif ($filterOperator === "like")
                    $builder->where($field,$filterOperator, '%'.$value.'%');
                elseif ($filterOperator === "gt")
                    $builder->where($field,">", $value);
                elseif ($filterOperator === "lt")
                    $builder->where($field,"<", $value);
                elseif ($filterOperator === "lte")
                    $builder->where($field,"<=", $value);
                elseif ($filterOperator === "gte")
                    $builder->where($field,">=", $value);
                else
                    $builder->where($field, $value);
            }
        }

        return $builder;
    }
}
