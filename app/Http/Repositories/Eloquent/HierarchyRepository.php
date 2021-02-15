<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\Hierarchy;

class HierarchyRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IHierarchyRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return Hierarchy::class;
    }
}
