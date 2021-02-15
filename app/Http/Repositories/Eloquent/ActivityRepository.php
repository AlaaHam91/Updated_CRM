<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\Activity;

class ActivityRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IActivityRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return Activity::class;
    }
}
