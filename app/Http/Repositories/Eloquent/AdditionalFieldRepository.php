<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\AdditionalField;

class AdditionalFieldRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IAdditionalFieldRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return AdditionalField::class;
    }

    /**
     * @param AdditionalField $f
     * @param $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function createAssignments(AdditionalField $f , $data)
    {
        return $f->assignments()->createMany($data['assignments']);
    }
}
