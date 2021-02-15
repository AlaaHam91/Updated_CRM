<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\IEducationRepository;
use App\Models\Education;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EducationController extends Controller
{
    /**
     * @var array
     */
    private $requestData;
    /**
     * @var IEducationRepository
     */
    private $educationRepository;

    public function __construct(IEducationRepository $educationRepository)
    {
        $this->requestData = Mapper::toUnderScore(\Request()->all());
        $this->educationRepository = $educationRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = $this->requestData;
        try
        {
            $models = $this->educationRepository->all();
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
            $rules = isset($data)?Education::CREATE_UPDATE_RULES:[];
            $validation = Validator::make($data, $rules, ValidatorHelper::messages());
            if($validation->passes())
            {
                $model = $this->educationRepository->create($data);
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
     * @param   $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try{
            $models = $this->educationRepository->find($id);
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
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try{
            $data = $this->requestData;
            try{
                $rules = isset($data)?Education::CREATE_UPDATE_RULES:[];
                $rules = $rules+['id'=>'required|numeric'];
                $data['id'] = $id;
                $validation = Validator::make($data, $rules, ValidatorHelper::messages());
                if($validation->passes())
                {
                    $this->educationRepository->update($data,$id);
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
        catch (\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param   $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try{
            $model = $this->educationRepository->find($id);
            $this->educationRepository->delete($model);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        }
        catch (\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }
}
