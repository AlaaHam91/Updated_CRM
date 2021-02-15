<?php


namespace App\Http\Repositories\Eloquent;
use App\Http\Repositories\IRepositories\ISocialNetworkRepository;
use App\Models\SocialNetwork;

class SocialNetworkRepository extends BaseRepository implements ISocialNetworkRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return SocialNetwork::class;
    }
}
