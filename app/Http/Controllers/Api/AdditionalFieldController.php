<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\IAdditionalFieldRepository;
use App\Http\Repositories\IRepositories\IFileUploaderRepository;
use App\Http\Repositories\IRepositories\INumberFieldRepository;
use App\Http\Repositories\IRepositories\ISelectMenuRepository;
use App\Http\Repositories\IRepositories\ITextFieldRepository;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Illuminate\Http\Request;
use Traits\TAdditionalField;

/**
 * Class AdditionalFieldController
 * @package App\Http\Controllers\Api
 * @author Kamal Mohammed
 */
class AdditionalFieldController extends Controller
{
    use TAdditionalField;

    /**
     * @var IAdditionalFieldRepository
     */
    private $additionalFieldRepository;
    /**
     * @var ITextFieldRepository
     */
    private $textFieldRepository;
    /**
     * @var INumberFieldRepository
     */
    private $numberFieldRepository;
    /**
     * @var ISelectMenuRepository
     */
    private $selectMenuRepository;
    /**
     * @var IFileUploaderRepository
     */
    private $fileUploaderRepository;
    /**
     * @var array
     */
    private $requestData;

    /**
     * AdditionalFieldController constructor.
     * @param IAdditionalFieldRepository $additionalFieldRepository
     * @param ITextFieldRepository $textFieldRepository
     * @param INumberFieldRepository $numberFieldRepository
     * @param ISelectMenuRepository $selectMenuRepository
     * @param IFileUploaderRepository $fileUploaderRepository
     */

    public function __construct(
        IAdditionalFieldRepository $additionalFieldRepository,
        ITextFieldRepository $textFieldRepository,
        INumberFieldRepository $numberFieldRepository,
        ISelectMenuRepository $selectMenuRepository,
        IFileUploaderRepository $fileUploaderRepository
    )
    {
        $this->additionalFieldRepository = $additionalFieldRepository;
        $this->textFieldRepository = $textFieldRepository;
        $this->numberFieldRepository = $numberFieldRepository;
        $this->selectMenuRepository = $selectMenuRepository;
        $this->fileUploaderRepository = $fileUploaderRepository;

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
          $models = $this->additionalFieldRepository->all();
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
        $data = $this->requestData;

        try {
            $this->saveFileds($data);
            $models = $this->additionalFieldRepository->all();
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$models);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try
        {
            $field = $this->additionalFieldRepository->find($id);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$field);
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
          $field = $this->additionalFieldRepository->find($id);
          $this->additionalFieldRepository->delete($field);
          return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY),$field);
        }
        catch(\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }
}
