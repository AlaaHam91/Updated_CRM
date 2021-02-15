<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Eloquent\CountryRepository;
use App\Http\Repositories\Eloquent\CurrencyRepository;
use App\Http\Repositories\Eloquent\CurrencyUnitRepository;
use App\Http\Repositories\IRepositories\ICityRepository;
use App\Http\Repositories\IRepositories\ICountryRepository;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use App\Models\CurrencyUnit;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class CountryController
 * @package App\Http\Controllers\Api
 * @author Kamal Mohammed
 */

class CountryController extends Controller
{
    protected $countryRepo;
    protected $cityRepo;
    protected $requestData;

    public function __construct(ICountryRepository $countryRepo,ICityRepository $cr){
        $this->countryRepo = $countryRepo;
        $this->cityRepo = $cr;
        $this->requestData = Mapper::toUnderScore(\Request()->all());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try
        {
            $models = $this->countryRepo->all();
            $models->load('cities');
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->requestData;
        try{
            $validator = self::prepareValidation($data);
            if($validator->passes())
            {
                $model = $this->countryRepo->create($data);
                $this->countryRepo->createRelated($model, "cities",$data['cities']);
                $model->load('cities');
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS), $model);
            }
            else
            {
                return JsonResponse::respondSuccess($validator->errors()->all());
            }
        }
        catch (\Exception $ex){
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
            $country = $this->countryRepo->find($id);
            $country->load('cities');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY), $country);
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
        try{
            $validator = self::prepareValidation($data);
            if($validator->passes())
            {
                $model = $this->countryRepo->find($id);
                    $this->countryRepo->update($data,$id);
                $this->cityRepo->updateOrCreateCity($id,$data['cities']);
                $model->load('cities');
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS), $model);
            }
            else
            {
                return JsonResponse::respondSuccess($validator->errors()->all());
            }
        }
        catch (\Exception $ex){
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
        try
       {
            $model = $this->countryRepo->find($id);
            echo $this->countryRepo->delete($model);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY),$model);
      }
       catch (\Exception $e)
        {
            return JsonResponse::respondError($e->getMessage());
       }
    }

    public function navigate($id)
    {
        try {
            $country = $this->countryRepo->find($id);
            if(!empty($country->id)) {
                // get previous user id
                $previous = $this->countryRepo->previous($country->id);
                // get next user id
                $next = $this->countryRepo->next($country->id);

                $country->load('cities');
                $data = ["payload" => $country, "previous" => $previous, "next" => $next];
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS), $data);
            }
           else
              return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS), []);
        }
        catch(\Exception $ex){
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    public static function prepareValidation($data)
    {
        $country = isset($data)?Country::CREATE_UPDATE_RULES:[];
        $cities = isset($data['cities'])?City::MASS_CREATE_RULES:[];
        $rules = $country+$cities;
        return Validator::make($data,$rules,ValidatorHelper::messages());
    }
}
