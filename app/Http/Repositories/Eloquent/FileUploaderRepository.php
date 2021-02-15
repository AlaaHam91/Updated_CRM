<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\FileUploader;

class FileUploaderRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IFileUploaderRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return FileUploader::class;
    }

    /**
     * @param FileUploader $field
     * @param $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createAdditionalField(FileUploader $field,$data)
    {
        return $field->additionalField()->create($data);
    }
}
