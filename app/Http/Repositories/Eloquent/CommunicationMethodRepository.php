<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\CommunicationMethod;
use Illuminate\Container\Container as App;

class CommunicationMethodRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\ICommunicationMethodRepository
{
    /**
     * @inheritDoc
     */
    function model()
    {
        return CommunicationMethod::class;
    }

}
