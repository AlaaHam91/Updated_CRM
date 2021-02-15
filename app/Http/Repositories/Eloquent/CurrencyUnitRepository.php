<?php


namespace App\Http\Repositories\Eloquent;

use App\Http\Repositories\IRepositories\ICurrencyUnitRepository;
use App\Models\CurrencyUnit;

class CurrencyUnitRepository extends BaseRepository implements ICurrencyUnitRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return CurrencyUnit::class;
    }
}
