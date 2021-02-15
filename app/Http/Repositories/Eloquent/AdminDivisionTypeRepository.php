<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\AdminDivisionType;

class AdminDivisionTypeRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IAdminDivisionTypeRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return AdminDivisionType::class;
    }
}
