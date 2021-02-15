<?php


namespace App\Http\Repositories\Eloquent;

use App\Http\Repositories\IRepositories\ICityRepository;
use App\Models\City;
use App\Models\Country;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;

class CityRepository extends BaseRepository implements ICityRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
       return City::class;
    }

    /**
     * @param $cid
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function updateOrCreateCity($cid , $data)
    {
        try {
            foreach ($data as $city) {
                if (isset($city['id']))
                    $this->model->update(['id' => $city['id'], 'country_id' => $cid], $city);
                else {
                    $city['country_id'] = $cid;
                    $this->model->create($city);
                }
            }
        }
        catch(\Exception $ex)
        {
            throw new \Exception(trans($ex->getMessage()));
        }
        return true;
    }
}
