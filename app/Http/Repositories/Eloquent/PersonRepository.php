<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\CompanyPerson;
use App\Models\Person;

class PersonRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IPersonRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return Person::class;
    }

    /**
     * @param Person $p
     * @param $info
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createAcquaintance(Person $p , $info){
        return $p->acquaintance()->create($info);
    }

    /**
     * @param Person $p
     * @param $info
     * @return CompanyPerson
     */
    public function createCompanyPerson(Person $p , $info):CompanyPerson
    {
        return $p->companyPerson()->create($info);
    }
}
