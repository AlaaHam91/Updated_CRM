<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\CompanyPerson;

class CompanyPeopleRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\ICompanyPeopleRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return CompanyPerson::class;
    }

    /**
     * @param Company $company
     * @param $relation_name
     * @param $data
     * @return mixed
     */
    public function syncRelated(Company $company , $relation_name , $data )
    {
        $relation = $company->{$relation_name}();
        return $relation->sync($data);
    }

    /**
     * @param CompanyPerson $cperson
     * @param $objects
     * @param $relationName
     * @return array
     */
    public function createManyRelated(CompanyPerson $cperson, $objects, $relationName)
    {
        if(count($objects)> 0 ) {
            $relation = $cperson->{$relationName}();
            $relation->delete();
            return $relation->createMany($objects);
        }
        return [];
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
     * @param CompanyPerson $cp
     * @param $relation_name
     * @param array $data
     * @return mixed
     */
    public function createRelatedLife(CompanyPerson $cp , $relation_name, $data=[])
    {
        $relation = $cp->{$relation_name}();
       if(count($data) > 0 )
       {
           $relation->delete();
           return $relation->create($data);
       }
       else
       {
           return $relation->create(['company_person_id'=>$cp->id]);
       }
    }

}
