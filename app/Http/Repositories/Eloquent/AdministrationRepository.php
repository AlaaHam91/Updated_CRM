<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\Administration;

class AdministrationRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IAdministrationRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return Administration::class;
    }
}
