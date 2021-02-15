<?php


namespace App\Http\Repositories\Eloquent;

use App\Http\Repositories\IRepositories\ICountryRepository;
use App\Models\City;
use App\Models\Country;

class CountryRepository extends BaseRepository implements ICountryRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return Country::class;
    }

    /**
     * @param Country $country
     * @param $relation
     * @param $data
     * @return mixed
     */
    public function createRelated(Country $country, $relation,$data)
    {
        return $country->{$relation}()->createMany($data);
    }

}
