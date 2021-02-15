<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\IBranchRepository;
use App\Models\Branch;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{

    /**
     * @var IBranchRepository
     */
    private $branchRepository;
    /**
     * @var array
     */
    private $requestData;


    public function __construct(IBranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
        $this->requestData = Mapper::toUnderScore(\Request()->all());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try{
            $models = $this->branchRepository->all();
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
            $rules = Branch::CREATE_RULES;
            $validation = Validator::make($data, $rules,ValidatorHelper::messages());
            if($validation->passes())
            {
                $model = $this->branchRepository->create($data);
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
            $models = $this->branchRepository->find($id);
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

            $rules = Branch::CREATE_RULES+['id'=>'required|exists:branches'];
            $validation = Validator::make($data, $rules,ValidatorHelper::messages());
            if($validation->passes())
            {
                $this->branchRepository->update($data,$id);
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
            $validation = Validator::make(['id'=>$id],['id'=>'required|exists:branches'],ValidatorHelper::messages());
            if($validation->passes())
            {
                $model = $this->branchRepository->find($id);
                $this->branchRepository->delete($model);
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
