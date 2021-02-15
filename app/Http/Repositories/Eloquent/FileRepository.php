<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\File;

class FileRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IFileRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return File::class;
    }
}
