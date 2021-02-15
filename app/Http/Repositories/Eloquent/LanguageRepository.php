<?php


namespace App\Http\Repositories\Eloquent;
use App\Http\Repositories\IRepositories\ILanguageRepository;
use App\Models\Language;

class LanguageRepository extends BaseRepository implements ILanguageRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return Language::class;
    }
}
