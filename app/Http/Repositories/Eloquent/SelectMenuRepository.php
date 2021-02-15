<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\SelectMenu;

class SelectMenuRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\ISelectMenuRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return SelectMenu::class;
    }

    /**
     * @param SelectMenu $field
     * @param $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createAdditionalField(SelectMenu $field,$data)
    {
        $select = $field->additionalField()->create($data);
        $field->options()->createMany($data['options']);

        return $select;
    }
}
