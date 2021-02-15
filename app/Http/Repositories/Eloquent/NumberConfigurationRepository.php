<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\NumberConfiguration;

class NumberConfigurationRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\INumberConfigurationRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return NumberConfiguration::class;
    }

    public function createRelated(NumberConfiguration $num ,$cmid)
    {
       return $num->configuration()->create(['communication_method_id'=>$cmid]);
    }
}
