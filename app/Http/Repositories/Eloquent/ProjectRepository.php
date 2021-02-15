<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\Project;

class ProjectRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IProjectRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return Project::class;
    }
}
