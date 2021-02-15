<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\IAcquaintanceMethodsRepository;
use App\Http\Repositories\IRepositories\IAddressConfigRepository;
use App\Models\AcquaintanceMethod;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class AcquaintanceMethodsController
 * @package App\Http\Controllers\Api
 * @author Kamal Mohammed
 */

class AcquaintanceMethodsController extends Controller
{

    protected $acquaintanceRepo;
    protected $requestData;

    public function __construct(IAcquaintanceMethodsRepository $repo)
    {
        $this->acquaintanceRepo = $repo;
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
            $models = $this->acquaintanceRepo->all();
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$models);
        }
        catch (\Exception $ex){
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
        try {
            $rules = AcquaintanceMethod::CREATE_UPDATE_RULES;
            $acq = isset($data) ? $data : null;
            $validation = Validator::make($acq,$rules,  ValidatorHelper::messages());
            if($validation->passes()){
                $models = $this->acquaintanceRepo->create($acq);
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$models,201);
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $models = $this->acquaintanceRepo->find($id);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$models);
        }
        catch (\Exception $ex){
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
        try {
            $rules = AcquaintanceMethod::CREATE_UPDATE_RULES;
            $acq = isset($data) ? $data : null;
            $validation = Validator::make($acq, $rules, ValidatorHelper::messages());
            if ($validation->passes()) {
                $models = $this->acquaintanceRepo->update($acq, $id);
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
            } else {
                return JsonResponse::respondError($validation->errors()->all());
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
        try {
            $object = $this->acquaintanceRepo->find($id);
            $this->acquaintanceRepo->delete($object);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));

        } catch (\Exception $ex) {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    /**
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function navigate($id=null)
    {
        //return "ID IS:".$id;

        try {
            $data = $this->acquaintanceRepo->find($id);
            if(!empty($data->id) && $id !== null) {
                // get previous user id
                $previous = $this->acquaintanceRepo->previous($data->id);
                // get next user id
                $next = $this->acquaintanceRepo->next($data->id);
                //first
                $first = $this->acquaintanceRepo->first()->id;
                //last
                $last = $this->acquaintanceRepo->latestRecord()->id;
                $data = ["payload" => $data,
                    "indices"=>[
                        "first"=>$first,
                        "previous" => $previous,
                        "next" => $next,
                        "last"=>$last,
                    ]
                ];
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS), $data);
            }
            else {
                $data = $this->acquaintanceRepo->latestRecord();
                //first
                $first = $this->acquaintanceRepo->first()->id;
                //prev
                $previous = $this->acquaintanceRepo->previous($data->id);
                // get next user id
                $next = null;

                $data = ["payload" => $data, "indices"=>[
                    "first"=>$first,
                    "previous" => $previous,
                    "next" => $next,
                    "last"=>null,
                ]
                ];
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS), $data);
            }
        }
        catch(\Exception $ex){
            return JsonResponse::respondError($ex->getMessage());
        }
    }
}
