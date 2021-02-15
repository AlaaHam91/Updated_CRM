<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\IHierarchyRepository;
use App\Models\Hierarchy;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HierarchyController extends Controller
{
    /**
     * @var IHierarchyRepository
     */
    private $hierarchyRepository;
    /**
     * @var array
     */
    private $requestData;

    public function __construct(IHierarchyRepository $hierarchyRepository)
    {
        $this->hierarchyRepository = $hierarchyRepository;
        $this->requestData = Mapper::toUnderScore(\Request()->all());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try{
            $models = $this->hierarchyRepository->all();
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
            $rules = Hierarchy::CREATE_RULES;
            $validation = Validator::make($data, $rules,ValidatorHelper::messages());
            if($validation->passes())
            {
                $model = $this->hierarchyRepository->create($data);
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
            $models = $this->hierarchyRepository->find($id);
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

            $rules = Hierarchy::CREATE_RULES+['id'=>'required|exists:hierarchies'];
            $validation = Validator::make($data, $rules,ValidatorHelper::messages());
            if($validation->passes())
            {
                $this->hierarchyRepository->update($data,$id);
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
            $validation = Validator::make(['id'=>$id],['id'=>'required|exists:hierarchies'],ValidatorHelper::messages());
            if($validation->passes())
            {
                $model = $this->hierarchyRepository->find($id);
                $this->hierarchyRepository->delete($model);
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

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTree()
    {
        try{
            $models = Hierarchy::get()->toTree();
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$models);
        }
        catch(\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }
}
