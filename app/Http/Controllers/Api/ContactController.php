<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\ICompanyPeopleRepository;
use App\Http\Repositories\IRepositories\IContactRepository;
use App\Http\Repositories\IRepositories\IPersonRepository;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Illuminate\Http\Request;
use Traits\TCompanyPerson;

/**
 * Class ContactController
 * @package App\Http\Controllers\Api
 * @author Kamal Mohammed
 */
class ContactController extends Controller
{
    use TCompanyPerson;
    /**
     * @var IContactRepository
     */
    private $contactRepo;
    /**
     * @var IPersonRepository
     */
    private $personRepo;
    /**
     * @var ICompanyPeopleRepository
     */
    private $companyPersonRepo;

    private $requestData;

    public function __construct(
        IContactRepository $contact,
        IPersonRepository $person,
        ICompanyPeopleRepository $companyPerson
    )
    {
        $this->contactRepo = $contact;
        $this->personRepo = $person;
        $this->companyPersonRepo = $companyPerson;
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
           $contacts = $this->contactRepo->all();
           $contacts->load('companyPerson.person','cpersonLife');
           return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$contacts);
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
         try {

         } catch (\Exception $ex) {
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
        //
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

