<?php


namespace App\Models;


use Helpers\Constants;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;


class AppModel extends Model
{
    use HasTranslations, CascadesDeletes;
    public $translatable = [];

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
