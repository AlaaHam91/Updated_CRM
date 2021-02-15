<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\EducationLevel;

class EducationLevelRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IEducationLevelRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return EducationLevel::class;
    }
}
