<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\AcquaintanceMethod;

class AcquaintanceMethodsRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IAcquaintanceMethodsRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return AcquaintanceMethod::class;
    }
}
