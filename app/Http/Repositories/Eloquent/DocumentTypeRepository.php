<?php


namespace App\Http\Repositories\Eloquent;
use App\Http\Repositories\IRepositories\IDocumentTypeRepository;
use App\Models\DocumentType;

class DocumentTypeRepository extends BaseRepository implements IDocumentTypeRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return DocumentType::class;
    }
}
