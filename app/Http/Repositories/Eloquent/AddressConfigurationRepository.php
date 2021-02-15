<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\AddressConfiguration;

class AddressConfigurationRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IAddressConfigurationRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return AddressConfiguration::class;
    }

    public function createRelated(AddressConfiguration $add , $da, $cmid)
    {
        $conf = $add->configuration()->create(['communication_method_id'=>$cmid]);
        $add->addressConfigurationDetails()->createMany($da);
        return $conf;
    }
}
