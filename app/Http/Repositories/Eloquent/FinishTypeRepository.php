<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\FinishType;

class FinishTypeRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IFinishTypeRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return FinishType::class;
    }
}
