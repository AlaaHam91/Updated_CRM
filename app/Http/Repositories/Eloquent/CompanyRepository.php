<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\CiMobile;
use App\Models\Company;
use App\Models\ContactInfo;
use Carbon\Carbon;

/**
 * Class CompanyRepository
 * @package App\Http\Repositories\Eloquent
 * @author Kamal Mohammed
 */
class CompanyRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\ICompanyRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return Company::class;
    }

    /**
     * @param Company $company
     * @param $files
     * @return array
     */
    public function syncFiles(Company $company , $files)
    {
     return $company->files()->sync($files);
    }
    public function syncActivities(Company $company,$activities){
        return $company->activities()->sync($activities);
    }

    /**
     * @param Company $company
     * @param $int
     * @return array
     */
    public function syncInterests(Company $company,$int){
        return $company->Interests()->sync($int);
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
     * @param Company $company
     * @param $objects
     * @param $relationName
     * @return mixed
     */
    public function createManyRelated(Company $company, $objects, $relationName)
    {
        $relation = $company->{$relationName}();
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
     * @param $company
     * @param $objects
     * @param $relationName
     * @return mixed
     */
    public function updateManyRelated($company, $objects, $relationName)
    {
        $relation   = $company->{$relationName}();
        $relation->delete();
        return $relation->createMany($objects);
    }

    /**
     * @param $company
     * @param $relations
     */
    public function deleteRelated($company,$relations)
    {
        foreach($relations as $relation)
        {
            $relation   = $company->{$relation}();
            $relation->delete();
        }
    }

    /**
     * @param Company $company
     * @param $data
     */
    public function createFields(Company $company, $data)
    {
        foreach($data['fields'] as $fid)
        {
            $company->fields()->attach($fid['additional_field_id'],['value'=>$fid["value"]]);
        }
    }

    /**
     * @param Company $company
     * @param $data
     */
    public function updateFields(Company $company , $data)
    {
        $company->fields()->detach();
        foreach($data['fields'] as $fid)
        {
            $company->fields()->attach($fid['additional_field_id'],['value'=>$fid["value"]]);
        }
    }

}
