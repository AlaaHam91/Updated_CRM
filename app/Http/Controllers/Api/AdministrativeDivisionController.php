<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\IAdminDivisionRepository;
use App\Models\AdminDivision;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class AdministrativeDivisionController
 * @package App\Http\Controllers\Api
 * @author Kamal Mohammed
 */
class AdministrativeDivisionController extends Controller
{

    /**
     * @var IAdminDivisionRepository
     */
    private $adminRepo;
    /**
     * @var array
     */
    private $requestData;

    /**
     * AdministrativeDivisionController constructor.
     * @param IAdminDivisionRepository $adv
     */

    public function __construct(IAdminDivisionRepository $adv)
    {
        $this->adminRepo = $adv;
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
            $models = $this->adminRepo->all();
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
        $data = $this->requestData;
        try{
            $rules = AdminDivision::CREATE_UPDATE_RULES;
            $validation = Validator::make($data, $rules, ValidatorHelper::messages());
            if($rules !== null && $validation->passes()){
                $models = $this->adminRepo->create($data);
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
            $models = $this->adminRepo->find($id);
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
        try{
            $data = $this->requestData;
            $rules = AdminDivision::CREATE_UPDATE_RULES;
            $validation = Validator::make($data, $rules, ValidatorHelper::messages());
            if($validation->passes()){
                $this->adminRepo->update($data,$id);
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
            $model= $this->adminRepo->find($id);
            $this->adminRepo->delete($model);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        }
        catch (\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }
}
