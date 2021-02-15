<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\TextField;

class TextFieldRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\ITextFieldRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
       return TextField::class;
    }

    /**
     * @param TextField $field
     * @param $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createAdditionalField(TextField $field,$data)
    {
        return $field->additionalField()->create($data);
    }
}
