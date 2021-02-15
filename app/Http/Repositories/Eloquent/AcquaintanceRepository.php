<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\Acquaintance;

class AcquaintanceRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IAcquaintanceRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return Acquaintance::class;
    }

    public function createManyRelated(Acquaintance $acquaintance, $objects, $relationName)
    {
        $relation = $acquaintance->{$relationName}();
        return $relation->createMany($objects);
    }

    /**
     * @param $type
     * @param $data
     * @param $req
     */
    public function syncSocials($type , $data, $req)
    {
        if($type === "emails")
        {
            foreach ($req as $item)
            {
                if(isset($item['socials']))
                {
                    foreach($data as $email)
                    {
                        if($email->number === $item['email'])
                            $email->socialNetworks()->sync($item['socials']);
                    }
                }
            }
        }
        else
        {
            foreach ($req as $item)
            {
                if(isset($item['socials']))
                {
                    foreach($data as $mobile)
                    {
                        if($mobile->number === $item['number'])
                            $mobile->socialNetworks()->sync($item['socials']);
                    }
                }
            }
        }
    }

    /**
     * @param Acquaintance $acquaintance
     * @param $objects
     * @param $relationName
     * @return mixed
     */
    public function updateManyRelated(Acquaintance $acquaintance, $objects, $relationName)
    {
        $relation   = $acquaintance->{$relationName}();
        $relation->delete();
        return $relation->createMany($objects);
    }

    /**
     * @param Acquaintance $acquaintance
     * @param $relations
     * @return mixed
     */
    public function deleteRelated(Acquaintance $acquaintance,$relations)
    {
        foreach($relations as $relation)
        {
            $relation   = $acquaintance->{$relation}();
            return $relation->delete();
        }
    }

    public function syncRelated(Acquaintance $acquaintance , $relation_name , $data )
    {
        $relation = $acquaintance->{$relation_name}();
        return $relation->sync($data);
    }
}
