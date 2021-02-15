<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\JobType;

class JobTypeRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IJobTypeRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return JobType::class;
    }
}
