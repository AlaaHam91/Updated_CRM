<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\Interest;

class InterestRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IInterestRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return Interest::class;
    }
}
