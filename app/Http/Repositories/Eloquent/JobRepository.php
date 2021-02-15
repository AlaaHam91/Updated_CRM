<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\Job;

class JobRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IJobRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return Job::class;
    }
}
