<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Helpers\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Traits\TCompanyPerson;

/**
 * Class AgentController
 * @package App\Http\Controllers\Api
 * @author Kamal Mohammed
 */
class AgentController extends Controller
{
    use TCompanyPerson;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $models = $this->cPersonRepo->all();
            $models->load('person');
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->requestData;
        if(request('person.image')){
            $data['person']['image'] = $request->file('person.image');
        }
        try
        {
            if(isset($data['action']) && $data['action'] === 'new')
            {
                return $this->createNewCompanyPerson($data,"agent");
            }
            else
            {
                $cid = $data['company_person_id'];
                return $this->updateOthersOrAddType($data, $cid,"agent");
            }
        }
        catch(\Exception $ex)
        {
            DB::rollBack();
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
            $models = $this->cPersonRepo->find($id);
            // $models->load('person');
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->requestData;
        if(request('person.image')){
            $data['person']['image'] = $request->file('person.image');
        }
        try
        {
            if(isset($data['action']) && $data['action'] === 'new')
            {
                return $this->createNewCompanyPerson($data,"agent");
            }
            else
            {
                $cid = $data['company_person_id'];
                return $this->updateOthersOrAddType($data, $cid,"agent");
            }
        }
        catch(\Exception $ex)
        {
            DB::rollBack();
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
        try{
            $model = $this->cPersonRepo->find($id);
            $this->cPersonRepo->delete($model);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        }
        catch(\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }
}
