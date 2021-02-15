<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\IJobTypeRepository;
use App\Models\JobType;
use Illuminate\Support\Facades\Validator;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;

/**
 * Class JobTypeController
 * @package App\Http\Controllers\Api
 * @author Kamal Mohammed
 */
class JobTypeController extends Controller
{
    protected $requestData;
    protected $jobTypeRepo;

    public function __construct(IJobTypeRepository $jobTypeRepo){
        $this->jobTypeRepo = $jobTypeRepo;
        $this->requestData = Mapper::toUnderScore(\Request()->all());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try
        {
            $models = $this->jobTypeRepo->all();
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS), $models);
        }
        catch (\Exception $e)
        {
            return JsonResponse::respondError($e->getMessage());
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
        try
        {
          $rules = isset($data)?JobType::CREATE_UPDATE_RULES:[];
          $validation = Validator::make($data,$rules,ValidatorHelper::messages());
          if($rules !== null && $validation->passes()){
              $models = $this->jobTypeRepo->create($data);
              return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY),$models,201);
          }
          return JsonResponse::respondError($validation->errors()->all());

        }
        catch (\Exception $e)
        {
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
        try {
            $country = $this->jobTypeRepo->find($id);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS), $country);
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
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
        try {
            $validation_rules = JobType::CREATE_UPDATE_RULES;
            $validator = Validator::make($data, $validation_rules, ValidatorHelper::messages());
            if ($validator->passes()) {
                $object = $this->jobTypeRepo->update($data, $id);
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
            }
            return JsonResponse::respondError($validator->errors()->all());
        } catch (\Exception $ex) {
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
                $object = $this->jobTypeRepo->find($id);
                $this->jobTypeRepo->delete($object);
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));

        } catch (\Exception $ex) {
            return JsonResponse::respondError($ex->getMessage());
        }
    }
}
