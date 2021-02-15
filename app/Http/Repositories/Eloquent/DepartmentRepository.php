<?php


namespace App\Http\Repositories\Eloquent;

use App\Http\Repositories\IRepositories\IDepartmentRepository;
use App\Models\Department;

class DepartmentRepository extends BaseRepository implements IDepartmentRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return Department::class;
    }
}
