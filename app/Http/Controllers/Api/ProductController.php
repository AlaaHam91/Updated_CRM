<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\IProductRepository;
use App\Models\Product;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * @var IProductRepository
     */
    private $productRepository;
    /**
     * @var array
     */
    private $requestData;

    /**
     * ProductController constructor.
     * @param IProductRepository $productRepository
     */
    public function __construct(IProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->requestData = Mapper::toUnderScore(\Request()->all());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try{
            $models = $this->productRepository->all()->toTree();
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
            $rules = Product::CREATE_RULES;
            $validation = Validator::make($data, $rules,ValidatorHelper::messages());
            if($validation->passes())
            {
                $model = $this->productRepository->create($data);
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
            $models = $this->productRepository->find($id);
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

            $rules = Product::CREATE_RULES+['id'=>'required|exists:hierarchies'];
            $validation = Validator::make($data, $rules,ValidatorHelper::messages());
            if($validation->passes())
            {
                $this->productRepository->update($data,$id);
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
            $validation = Validator::make(['id'=>$id],['id'=>'required|exists:products'],ValidatorHelper::messages());
            if($validation->passes())
            {
                $model = $this->productRepository->find($id);
                $this->productRepository->delete($model);
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
            $models = $this->productRepository->getTree();
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$models);
        }
        catch(\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }
}
