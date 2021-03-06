<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Product extends AppModel
{
    use NodeTrait;

    protected $fillable = ['name','latin_name','parent_id','type'];

    protected $guarded = ['lft','rgt','created_at','updated_at'];

    protected $hidden = ['lft','rgt','created_at','updated_at'];

    public const CREATE_RULES = [
        'name'=>'required|string',
        'latin_name'=>'sometimes|string',
        'type'=>'required|in:main,branch',
        'parent_id'=>'sometimes|required|numeric',
    ];

    public function agents()
    {
        return $this->belongsToMany(Agent::class,'agent_products');
    }

    public function children()
    {
        return $this->hasMany(\App\Models\Product::class,'parent_id');
    }




}
