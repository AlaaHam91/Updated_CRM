<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\IAdministrationRepository;
use App\Models\Administration;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdministrationController extends Controller
{
    /**
     * @var IAdministrationRepository
     */
    private $administrationRepository;
    /**
     * @var array
     */
    private $requestData;

    /**
     * AdministrationController constructor.
     * @param IAdministrationRepository $administrationRepository
     */
    public function __construct(IAdministrationRepository $administrationRepository)
    {
        $this->administrationRepository = $administrationRepository;
        $this->requestData = Mapper::toUnderScore(\Request()->all());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try{
            $models = $this->administrationRepository->all();
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$models);
        }
        catch(\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try{
           $data = $this->requestData;
           $rules = Administration::CREATE_RULES;
           $validation = Validator::make($data, $rules,ValidatorHelper::messages());
           if($validation->passes())
           {
               $model = $this->administrationRepository->create($data);
               return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$model);
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
            $models = $this->administrationRepository->find($id);
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
        try{
            $data = $this->requestData;
            $data['id'] = $id;

            $rules = Administration::CREATE_RULES+['id'=>'required|exists:administrations'];
            $validation = Validator::make($data, $rules,ValidatorHelper::messages());
            if($validation->passes())
            {
                $this->administrationRepository->update($data,$id);
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try
        {
           $validation = Validator::make(['id'=>$id],['id'=>'required|exists:administrations'],ValidatorHelper::messages());
           if($validation->passes())
           {
               $model = $this->administrationRepository->find($id);
               $this->administrationRepository->delete($model);
               return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
           }
           else
           {
               return JsonResponse::respondError($validation->errors()->all());
           }
        }
        catch (\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }
}
