<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\Currency;

class CurrencyRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\ICurrencyRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return Currency::class;
    }

    /**
     * @param Currency $currency
     * @param $units
     */
    public function createUnits(Currency $currency , $units)
    {
        $currency->units()->delete();
        $currency->units()->createMany($units);
    }

    /**
     * @param Currency $currency
     * @param $rates
     */
    public function createRates(Currency $currency , $rates)
    {
        $currency->rates()->delete();
        $currency->rates()->createMany($rates);
    }
}
