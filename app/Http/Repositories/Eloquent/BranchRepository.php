<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\Branch;

class BranchRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IBranchRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return Branch::class;
    }
}
