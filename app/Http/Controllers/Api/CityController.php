<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\ICityRepository;
use App\Models\City;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class CityController
 * @package App\Http\Controllers\Api
 * @author Kamal Mohammed
 */
class CityController extends Controller
{
    protected $cityRepository;
    protected $requestData;

    public function __construct(ICityRepository $cityrepo)
    {
       $this->cityRepository = $cityrepo;
       $this->requestData = Mapper::toUnderScore(\Request()->all());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try
        {
            $models = $this->cityRepository->all();
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS), $models);
        }
        catch (\Exception $e)
        {
            return JsonResponse::respondError($e->getMessage());
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->requestData;
        try{
           $rules = City::CREATE_UPDATE_RULES;
           $validation = Validator::make($data,$rules);
           if($validation->validated()){
               $city = $this->cityRepository->create($data);
               return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY), $city);
           }

            return JsonResponse::respondError($validation->errors()->all());
        }
        catch (\Exception $e){
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $city = $this->cityRepository->find($id);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS), $city);
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->requestData;
        $validation_rules = City::CREATE_UPDATE_RULES;
        $validator = Validator::make($data, $validation_rules, ValidatorHelper::messages());
        try{
            $city = $this->cityRepository->find($id);
            if ($validator->passes()) {
                $object = $this->cityRepository->update($data, $city->id);
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
            }
            return JsonResponse::respondError($validator->errors()->all());

        }
        catch (\Exception $e){
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

                $city = $this->cityRepository->find($id);
                $this->cityRepository->delete($city);
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));

        } catch (\Exception $ex) {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    public function deleteCity(City $city)
    {

        try {
            $this->cityRepository->delete($city);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));

        } catch (\Exception $ex) {
            return JsonResponse::respondError($ex->getMessage());
        }
    }
}
