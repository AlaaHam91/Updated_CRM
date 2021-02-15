<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\ICompanyPeopleRepository;
use App\Http\Repositories\IRepositories\IContactRepository;
use App\Http\Repositories\IRepositories\IPersonRepository;
use App\Models\CiEmail;
use App\Models\CiFax;
use App\Models\CiMobile;
use App\Models\CiNote;
use App\Models\CiPhone;
use App\Models\CompanyPerson;
use App\Models\Contact;
use App\Models\Person;
use App\Rules\NumberLength;
use App\Rules\NumberPrefix;
use App\Rules\PhoneLength;
use App\Rules\PhonePrefixes;
use Helpers\FileHelper;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Traits\TCompanyPerson;

/**
 * Class CompanyPeopleController
 * @package App\Http\Controllers\Api
 * @author Kamal Mohammed
 */
class CompanyPeopleController extends Controller
{
    use TCompanyPerson;

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try{
          $models = $this->cPersonRepo->all();
          $models->load('person');
          return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$models);
        }
        catch(\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $this->requestData;
        if(request('person.image')){
            $data['person']['image'] = $request->file('person.image');
        }
        try
        {
            if(isset($data['action']) && $data['action'] === 'new')
            {
               return $this->createNewCompanyPerson($data,"contact");
            }
            else
            {
               $cid = $data['company_person_id'];
               return $this->updateOthersOrAddType($data, $cid,"contact");
            }
       }
        catch(\Exception $ex)
        {
           DB::rollBack();
            return JsonResponse::respondError($ex->getMessage());
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try{
            $models = $this->cPersonRepo->find($id);
           // $models->load('person');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$models);
        }
        catch(\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $data = $this->requestData;
        if(request('person.image')){
            $data['person']['image'] = $request->file('person.image');
        }
        try
        {
            if(isset($data['action']) && $data['action'] === 'new')
            {
                return $this->createNewCompanyPerson($data,'contact');
            }
            else
            {
                $cid = $data['company_person_id'];
                return $this->updateOthersOrAddType($data, $cid);
            }
        }
        catch(\Exception $ex)
        {
            DB::rollBack();
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try{
            $model = $this->cPersonRepo->find($id);
            $this->cPersonRepo->delete($model);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        }
        catch(\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

}
