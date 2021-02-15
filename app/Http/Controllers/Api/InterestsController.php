<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\IInterestRepository;
use App\Models\Interest;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class InterestsController
 * @package App\Http\Controllers\Api
 * @author Kamal Mohammed
 */
class InterestsController extends Controller
{
    protected $interestRepo;
    protected $requestData;

    public function __construct(IInterestRepository $interestRepo){
        $this->interestRepo = $interestRepo;
        $this->requestData = Mapper::toUnderScore(\Request()->all());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try{
            $models = $this->interestRepo->all();
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
            $rules = Interest::CREATE_UPDATE_RULES;
            $validation = Validator::make($data, $rules, ValidatorHelper::messages());
            if($rules !== null && $validation->passes()){
                $models = $this->interestRepo->create($data);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $models = $this->interestRepo->find($id);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $data = $this->requestData;
        try {
            $validation_rules = Interest::CREATE_UPDATE_RULES;
            $validator = Validator::make($data, $validation_rules, ValidatorHelper::messages());
            if ($validator->passes()) {
                $this->interestRepo->update($data, $id);
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
            $object = $this->interestRepo->find($id);
            $this->interestRepo->delete($object);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));

        } catch (\Exception $ex) {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function navigate($id=null)
    {
        //return "ID IS:".$id;

        try {
            $data = $this->interestRepo->find($id);
            if(!empty($data->id) && $id !== null) {
                // get previous user id
                $previous = $this->interestRepo->previous($data->id);
                // get next user id
                $next = $this->interestRepo->next($data->id);
                //first
                $first = $this->interestRepo->first()->id;
               //last
                $last = $this->interestRepo->latestRecord()->id;
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
                $data = $this->interestRepo->latestRecord();
                //first
                $first = $this->interestRepo->first()->id;
                //prev
                $previous = $this->interestRepo->previous($data->id);
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
