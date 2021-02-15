<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\ICurrencyRepository;
use App\Models\Currency;
use App\Models\CurrencyRate;
use App\Models\CurrencyUnit;
use Helpers\FileHelper;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Class CurrencyController
 * @package App\Http\Controllers\Api
 * @author Kamal Mohammed
 */
class CurrencyController extends Controller
{
    protected $currencyRepo;
    protected $requestData;

    public function __construct(ICurrencyRepository $repository)
    {
        $this->currencyRepo = $repository;
        $this->requestData = Mapper::toUnderScore(\Request()->all());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $models = $this->currencyRepo->all();
            $models->load('units','rates');
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->requestData;
        if(request('currency.logo')){
            $data['currency']['logo'] = $request->file('currency.logo');
        }
        try {
           $validation = self::prepareValidation($data);
           if($validation->passes())
           {
               if(isset($data['currency']['logo'])){
                   $data['currency']['logo'] = FileHelper::uploadFiles([$data['currency']['logo']] ,"currency/logo")[0];
               }
               $model = $this->currencyRepo->create($data['currency']);
               $this->currencyRepo->createUnits($model, $data['currency']['units']??[]);
               $this->currencyRepo->createRates($model, $data['currency']['rates']??[]);
               $model->load('units','rates');
               return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$model);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $models = $this->currencyRepo->find($id);
            $models = isset($models->id)?$models->load('units','rates'):[];
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->requestData;

        if(request('currency.logo')){
            $data['currency']['logo'] = $request->file('currency.logo');
        }

        try {
            $validation = self::prepareValidation($data);
            if($validation->passes())
            {
                $model = $this->currencyRepo->find($id);

                if(isset($data['currency']['logo'])){
                    FileHelper::removeFiles($model->logo);
                    $data['currency']['logo'] = FileHelper::uploadFiles([$data['currency']['logo']] ,"currency/logo")[0];
                }

                $this->currencyRepo->update($data['currency'],$id);
                $this->currencyRepo->createUnits($model, $data['currency']['units']);
                $this->currencyRepo->createRates($model, $data['currency']['rates']);
                $model->load('units','rates');
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY),$model);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $model = $this->currencyRepo->find($id);
            $this->currencyRepo->delete($model);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        }
        catch (\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    public static function prepareValidation($data)
    {
        $currency = isset($data['currency'])?Currency::CREATE_UPDATE_RULES:[];
        $currency += (isset($data['currency']['active'])&&$data['active']==1)?['units'=>'required','rates'=>Rule::requiredIf(Currency::all()->count()>0)]:[];
        $units    = isset($data['currency']['units'])?CurrencyUnit::CREATE_UPDATE_RULES:[];
        $rates    = isset($data['currency']['rates'])?CurrencyRate::CREATE_UPDATE_RULES:[];
        $rules = $currency+$units+$rates;
        return Validator::make($data['currency'],$rules,ValidatorHelper::messages());
    }

    public function navigate($id)
    {
        try {
            $currency = $this->currencyRepo->find($id);
            if(!empty($currency->id)) {
                // get previous user id
                $previous = $this->currencyRepo->previous($currency->id);
                // get next user id
                $next = $this->currencyRepo->next($currency->id);
                $currency->load('units','rates');
                $data = ["payload" => $currency, "previous" => $previous, "next" => $next];
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS), $data);
            }
            else
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS), []);
        }
        catch(\Exception $ex){
            return JsonResponse::respondError($ex->getMessage());
        }
    }

}
