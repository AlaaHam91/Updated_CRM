<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\AdminDivision;

class AdminDivisionRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IAdminDivisionRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return AdminDivision::class;
    }
}
