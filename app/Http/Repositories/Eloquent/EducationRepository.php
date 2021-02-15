<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\Education;

class EducationRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IEducationRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return Education::class;
    }
}
