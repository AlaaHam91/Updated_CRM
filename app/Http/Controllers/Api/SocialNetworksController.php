<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\ISocialNetworkRepository;
use App\Models\SocialNetwork;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class SocialNetworksController
 * @package App\Http\Controllers\Api
 * @author Kamal Mohammed
 */
class SocialNetworksController extends Controller
{
    protected $socialRepo;
    protected $requestData;

    public function __construct(ISocialNetworkRepository $socialRepo){
        $this->socialRepo = $socialRepo;
        $this->requestData = Mapper::toUnderScore(\Request()->all());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $models = $this->socialRepo->all();
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$models);
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->requestData;
        try{
            $rules = SocialNetwork::CREATE_UPDATE_RULES;
            $validation = Validator::make($data, $rules, ValidatorHelper::messages());
            if($rules !== null && $validation->passes()){
                $models = $this->socialRepo->create($data);
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $models = $this->socialRepo->find($id);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS), $models);
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->requestData;
        try {
            $validation_rules = SocialNetwork::CREATE_UPDATE_RULES;
            $validator = Validator::make($data, $validation_rules, ValidatorHelper::messages());
            if ($validator->passes()) {
                $this->socialRepo->update($data, $id);
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $object = $this->socialRepo->find($id);
            $this->socialRepo->delete($object);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));

        } catch (\Exception $ex) {
            return JsonResponse::respondError($ex->getMessage());
        }
    }
}
