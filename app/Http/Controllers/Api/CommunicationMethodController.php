<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\IAddressConfigurationRepository;
use App\Http\Repositories\IRepositories\ICommunicationMethodRepository;
use App\Http\Repositories\IRepositories\INumberConfigurationRepository;
use App\Models\AddressConfiguration;
use App\Models\AddressConfigurationDetail;
use App\Models\CommunicationMethod;
use App\Models\ContactConfiguration;
use App\Models\NumberConfiguration;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Traits\TCommMethods;

class CommunicationMethodController extends Controller
{
    use TCommMethods;
    /**
     * @var ICommunicationMethodRepository
     */
    private $commRepo;
    /**
     * @var array
     */
    private $requestData;
    /**
     * @var IAddressConfigurationRepository
     */
    private $addRepo;
    /**
     * @var INumberConfigurationRepository
     */
    private $numRepo;

    /**
     * CommunicationMethodController constructor.
     * @param ICommunicationMethodRepository $crepo
     * @param IAddressConfigurationRepository $addRepo
     * @param INumberConfigurationRepository $numRepo
     */
    public function __construct(
        ICommunicationMethodRepository $crepo,
        IAddressConfigurationRepository $addRepo,
        INumberConfigurationRepository $numRepo
       )
    {
        $this->commRepo = $crepo;
        $this->addRepo = $addRepo;
        $this->numRepo = $numRepo;
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
            $models = $this->commRepo->all();
            $models->load('contactConfiguration');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$models);
        }
        catch(\Exception $ex)
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
            $data = $this->requestData;
            $validation = Validator::make($data, self::rules($data),ValidatorHelper::messages());
            if($validation->passes())
            {
                $model = $this->saveMethod($data);
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$model);
            }
            else
                return JsonResponse::respondError($validation->errors()->all());

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
        try {
           $models = $this->commRepo->find($id);
            $models->load('contactConfiguration');
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
        try {
            $data = $this->requestData;
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
        try {
           $model = $this->commRepo->find($id);
           $this->commRepo->delete($model);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        }
        catch(\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    public static function rules($data)
    {
        $main = CommunicationMethod::CREATE_UPDATE_RULES;
        $mobile = isset($data['mobile'])?CommunicationMethod::MOBILE_RULES:[];
        $phone = isset($data['phone'])?CommunicationMethod::PHONE_RULES:[];
        $fax = isset($data['fax'])?CommunicationMethod::FAX_RULES:[];
        $address = isset($data['address'])?AddressConfiguration::CREATE_UPDATE_RULES:[];
        $details = isset($data['address']['detail'])?AddressConfigurationDetail::CREATE_UPDATE_RULES:[];

        return $main+$mobile+$phone+$fax+$address+$details;
    }
}
