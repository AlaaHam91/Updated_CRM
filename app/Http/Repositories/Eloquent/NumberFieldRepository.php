<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\NumberField;

class NumberFieldRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\INumberFieldRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return NumberField::class;
    }

    /**
     * @param NumberField $field
     * @param $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createAdditionalField(NumberField $field,$data)
    {
        return $field->additionalField()->create($data);
    }
}
