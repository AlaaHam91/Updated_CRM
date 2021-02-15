<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\IAdminDivisionTypeRepository;
use App\Models\AdminDivisionType;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class AdministrativeDivisionTypeController
 * @package App\Http\Controllers\Api
 * @author Kamal Mohammed
 */
class AdministrativeDivisionTypeController extends Controller
{
    /**
     * @var IAdminDivisionTypeRepository
     */
    private $typeRepo;
    /**
     * @var array
     */
    private $requestData;

    /**
     * AdministrativeDivisionTypeController constructor.
     * @param IAdminDivisionTypeRepository $ty
     */

    public function __construct(IAdminDivisionTypeRepository $ty)
    {
        $this->typeRepo = $ty;
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
            $models = $this->typeRepo->all();
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$models);
        }
        catch (\Exception $ex)
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
        try{
            $data = $this->requestData;
            $rules = AdminDivisionType::CREATE_UPDATE_RULES;
            $validation = Validator::make($data, $rules, ValidatorHelper::messages());
            if($validation->passes()){
                $models = $this->typeRepo->create($data);
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY),$models,201);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try{
            $models = $this->typeRepo->find($id);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$models);
        }
        catch (\Exception $ex)
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
        try
        {
            $data = $this->requestData;
            $rules = AdminDivisionType::CREATE_UPDATE_RULES;
            $validation = Validator::make($data, $rules, ValidatorHelper::messages());
            if($validation->passes()){
                $this->typeRepo->update($data,$id);
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
            }
            return JsonResponse::respondError($validation->errors()->all());
        }
        catch (\Exception $e){
            return JsonResponse::respondError($e->getMessage());
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
            $model= $this->typeRepo->find($id);
            $this->typeRepo->delete($model);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        }
        catch (\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }
}
