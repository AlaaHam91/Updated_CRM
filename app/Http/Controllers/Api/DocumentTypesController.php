<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\IDocumentTypeRepository;
use App\Models\DocumentType;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class DocumentTypesController
 * @package App\Http\Controllers\Api
 * @author Kamal Mohammed
 */
class DocumentTypesController extends Controller
{
    /**
     * @var IDocumentTypeRepository
     */
    protected $documentTypeRepo;
    /**
     * @var array
     */
    protected $requestData;

    /**
     * DocumentTypesController constructor.
     * @param IDocumentTypeRepository $documentTypeRepo
     */
    public function __construct(IDocumentTypeRepository $documentTypeRepo){
        $this->documentTypeRepo = $documentTypeRepo;
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
            $models = $this->documentTypeRepo->all();
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $this->requestData;
        try{
            $rules = DocumentType::CREATE_UPDATE_RULES;
            $validation = Validator::make($data, $rules, ValidatorHelper::messages());
            if($validation->passes()){
                $models = $this->documentTypeRepo->create($data);
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
            $models = $this->documentTypeRepo->find($id);
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
            $validation_rules = DocumentType::CREATE_UPDATE_RULES;
            $validator = Validator::make($data, $validation_rules, ValidatorHelper::messages());
            if ($validator->passes()) {
                $this->documentTypeRepo->update($data, $id);
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
            $object = $this->documentTypeRepo->find($id);
            $this->documentTypeRepo->delete($object);
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
        try {
            $data = $this->documentTypeRepo->find($id);
            if(!empty($data->id) && $id !== null) {
                // get previous user id
                $previous = $this->documentTypeRepo->previous($data->id);
                // get next user id
                $next = $this->documentTypeRepo->next($data->id);
                //first
                $first = $this->documentTypeRepo->first()->id;
                //last
                $last = $this->documentTypeRepo->latestRecord()->id;
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
                $data = $this->documentTypeRepo->latestRecord();
                //first
                $first = $this->documentTypeRepo->first()->id;
                //prev
                $previous = $this->documentTypeRepo->previous($data->id);
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
