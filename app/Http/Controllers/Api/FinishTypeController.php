<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\IFinishTypeRepository;
use App\Models\FinishType;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FinishTypeController extends Controller
{

    /**
     * @var IFinishTypeRepository
     */
    private $finishTypeRepository;
    /**
     * @var array
     */
    private $requestData;

    /**
     * FinishTypeController constructor.
     * @param IFinishTypeRepository $finishTypeRepository
     */

    public function __construct(IFinishTypeRepository $finishTypeRepository)
    {
        $this->finishTypeRepository = $finishTypeRepository;
        $this->requestData = Mapper::toUnderScore(\Request()->all());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $models = $this->finishTypeRepository->all();
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
        try {
            $rules = ['name'=>'required','latin_name'=>'required'];
            $data = $this->requestData;
            $validation = Validator::make($data,$rules,ValidatorHelper::messages());
            if($validation->passes())
            {
                $model = $this->finishTypeRepository->create($data);
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY),$model,201);
            }
            else
            return JsonResponse::respondError($validation->errors()->all());
        }
        catch (\Exception $ex)
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
        try {
            $models = $this->finishTypeRepository->find($id);
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
        try {
            $rules = ['name'=>'required','latin_name'=>'required','id'=>'required'];
            $data = $this->requestData;
            $data['id']= $id;
            $validation = Validator::make($data,$rules,ValidatorHelper::messages());
            if($validation->passes())
            {
                $model = $this->finishTypeRepository->update($data,$id);
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
            }
            else
                return JsonResponse::respondError($validation->errors()->all());
        }
        catch (\Exception $ex)
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
        try {
            $model = $this->finishTypeRepository->find($id);
            $this->finishTypeRepository->delete($model);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        }
        catch (\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }
}
