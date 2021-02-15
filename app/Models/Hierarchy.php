<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Hierarchy extends AppModel
{
    use NodeTrait;

    protected $fillable = ['name','latin_name','type','parent_id'];

    protected $guarded = ['lft','rgt','created_at','updated_at'];

    protected $hidden = ['lft','rgt','created_at','updated_at'];

    public const CREATE_RULES = [
        'name'=>'required|string',
        'latin_name'=>'sometimes|string',
        'type'=>'required|in:main,branch',
        'parent_id'=>'sometimes|required|numeric',
    ];

    public function parent()
    {
        return $this->belongsTo(\App\Models\Hierarchy::class,'parent_id')->where('parent_id',null);
    }

    public function children()
    {
        return $this->hasMany(\App\Models\Hierarchy::class,'parent_id');
    }


}
