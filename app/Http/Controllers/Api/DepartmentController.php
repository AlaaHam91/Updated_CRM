<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\IDepartmentRepository;
use App\Models\Department;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * @var array
     */
    private $requestData;
    /**
     * @var IDepartmentRepository
     */
    private $departmentRepository;

    /**
     * DepartmentController constructor.
     * @param IDepartmentRepository $departmentRepository
     */

    public function __construct(IDepartmentRepository $departmentRepository)
    {
        $this->requestData = Mapper::toUnderScore(\Request()->all());
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try{
            $models = $this->departmentRepository->all();
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
            $rules = Department::CREATE_RULES;
            $validation = Validator::make($data, $rules,ValidatorHelper::messages());
            if($validation->passes())
            {
                $model = $this->departmentRepository->create($data);
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
            $models = $this->departmentRepository->find($id);
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

            $rules = Department::CREATE_RULES+['id'=>'required|exists:departments'];
            $validation = Validator::make($data, $rules,ValidatorHelper::messages());
            if($validation->passes())
            {
                $this->departmentRepository->update($data,$id);
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
            $validation = Validator::make(['id'=>$id],['id'=>'required|exists:departments'],ValidatorHelper::messages());
            if($validation->passes())
            {
                $model = $this->departmentRepository->find($id);
                $this->departmentRepository->delete($model);
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
