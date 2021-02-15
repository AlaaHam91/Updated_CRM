<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\ContactSource;

class ContactSourcesRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IContactSourcesRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return ContactSource::class;
    }
}
