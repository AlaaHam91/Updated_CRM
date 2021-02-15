<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\IContactSourcesRepository;
use App\Models\ContactSource;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class ContactSourcesController
 * @package App\Http\Controllers\Api
 * @author Kamal Mohammed
 */
class ContactSourcesController extends Controller
{
    protected $contactSourcesRepo;
    protected $requestData;

    public function __construct(IContactSourcesRepository $repo){
        $this->contactSourcesRepo = $repo;
        $this->requestData = Mapper::toUnderScore(\Request()->all());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try{
            $models = $this->contactSourcesRepo->all();
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$models);
        }
        catch (\Exception $ex){
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
        try {
            $data = $this->requestData;
            $rules = ContactSource::CREATE_UPDATE_RULES;

            $validation = Validator::make($data, $rules, ValidatorHelper::messages());
            if($validation->passes()){
                  $models = $this->contactSourcesRepo->create($data);
                  return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$models,201);
            }
            else
            {
                return JsonResponse::respondError($validation->errors()->all());
            }
        }
        catch(\Exception $ex)
        {
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
            $models = $this->contactSourcesRepo->find($id);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$models);
        }
        catch (\Exception $ex){
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

        try{
            $rules = ContactSource::CREATE_UPDATE_RULES;
            $validation = Validator::make( $data , $rules,ValidatorHelper::messages());
            if($validation->passes()){
                $this->contactSourcesRepo->update($data, $id);
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
            }
           else
           {
               return JsonResponse::respondError($validation->errors()->all());
           }
        }
        catch (\Exception $ex){
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
        try {
            $object = $this->contactSourcesRepo->find($id);
            $this->contactSourcesRepo->delete($object);
            return JsonResponse::respondSuccess(JsonResponse::MSG_DELETED_SUCCESSFULLY);;

        } catch (\Exception $ex) {
            return JsonResponse::respondError($ex->getMessage());
        }
    }
}
