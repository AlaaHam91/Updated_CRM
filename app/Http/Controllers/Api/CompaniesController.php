<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\ICompanyRepository;
use App\Http\Repositories\IRepositories\IFileRepository;
use App\Models\CiAddress;
use App\Models\CiEmail;
use App\Models\CiFax;
use App\Models\CiMobile;
use App\Models\CiNote;
use App\Models\CiPhone;
use App\Models\CiPosition;
use App\Models\Company;
use App\Models\File;
use App\Rules\NumberLength;
use App\Rules\NumberPrefix;
use App\Rules\PhoneLength;
use App\Rules\PhonePrefixes;
use Helpers\FileHelper;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Class CompaniesController
 * @package App\Http\Controllers\Api
 * @author Kamal Mohammed
 */
class CompaniesController extends Controller
{
    protected $companiesRepo;
    protected $fileRepo;
    protected $requestData;

    public function __construct(ICompanyRepository $repo,IFileRepository $fr)
    {
        $this->companiesRepo = $repo;
        $this->fileRepo = $fr;
        $this->requestData = Mapper::toUnderScore(\Request()->all());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = $this->requestData;
        try
        {
              $models = $this->companiesRepo->all();
              return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY),$models,201);
        }
        catch(\Exception $ex){
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

        try{
            $data = $this->requestData;
            if(request('logo')){
                $data['logo'] = $request->file('logo');

            }
            $company_validator = Validator::make($data ,$this->getRules($data),ValidatorHelper::messages());
            if($company_validator->passes())
            {
                if(isset($data['logo'])){
                    $data['logo'] = FileHelper::uploadFiles([$data['logo']] ,"companies/logo")[0];
                }
                $file_ids = $this->createFiles($request, $data);

                $company = $this->companiesRepo->create($data);
                $this->companiesRepo->syncFiles($company , $file_ids);
                $this->companiesRepo->syncRelated($company,'activities',$data['activities']);
                $this->companiesRepo->syncRelated($company,'interests',$data['interests']);
                $this->companiesRepo->syncRelated($company,'sharedUsers',$data['sharing']);
                isset($data['phones'])?$this->companiesRepo->createManyRelated($company,$data['phones'],"phones"):"";
                isset($data['notes'])?$this->companiesRepo->createManyRelated($company,$data['notes'],"notes"):"";
                isset($data['faxes'])?$this->companiesRepo->createManyRelated($company,$data['faxes'],"faxes"):"";
                $mobiles = isset($data['mobiles'])?$this->companiesRepo->createManyRelated($company,$data['mobiles'],"mobiles"):[];
                $this->companiesRepo->syncSocials("mobiles" , $mobiles, $data['mobiles']??[]);
                $emails = isset($data['emails'])?$this->companiesRepo->createManyRelated($company,$data['emails'],"emails"):[];
                $this->companiesRepo->syncSocials("emails" , $emails, $data['emails']??[]);
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY),$company,201);
            }
            else
            {
                return JsonResponse::respondError($company_validator->errors()->all());
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try{
            $company = $this->companiesRepo->find($id);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$company);
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
        $data = $this->requestData;
        if($request->file('logo')){
            $data['logo'] = $request->file('logo');
        }

        if($request->file('files')){
            $data['files'] = $request->file('files');
        }
        try {
            $company = $this->companiesRepo->find($id);
            $phones_rules = !empty($data['phones'])?
                CiPhone::PHONE_RULES+["phones.*.number"=>['required',
                    new PhoneLength($data["phones"]),
                    new PhonePrefixes($data["phones"])]]:[];
            $mobiles_rules = !empty($data['mobiles'])?
                CiMobile::MOBILE_RULES+["mobiles.*.number"=>['required',
                    new NumberLength($data["mobiles"]),
                    new NumberPrefix($data["mobiles"])]]:[];
            $faxes_rules = !empty($data['faxes'])?
                CiFax::FAX_RULES+["faxes.*.number"=>['required',
                    new PhoneLength($data["faxes"]),
                    new PhonePrefixes($data["faxes"])]]:[];

            $emails_rules = !empty($data['emails'])?CiEmail::EMAIL_RULES:[];
            $notes_rules = !empty($data['notes'])?CiNote::NOTE_RULES:[];
            // $addresses_rules = !empty($data['addresses'])?CiAddress::ADDRESS_RULES:[];
            $files_rules = !empty($data['files'])?File::CREATE_UPDATE_RULES:[];

            $validation_rules = Company::CREATE_RULES + $phones_rules+ $mobiles_rules+ $emails_rules+ $notes_rules+ $faxes_rules+$files_rules;
            $validation_rules['id'] = "required|".Rule::unique('companies','id')->ignore($company->id);
            $validation_rules['name'] = "required|".Rule::unique('companies','name')->ignore($company->id);
            $validation_rules['latin_name'] = "required|".Rule::unique('companies','latin_name')->ignore($company->id);

            $company_validator = Validator::make($data, $validation_rules, ValidatorHelper::messages());
            if ($company_validator->passes() ){
                //if the user does not prefer to type code
               if(!isset($data['id']) ){
                    $data['code'] = $company->code;
                }
                //updaload logo
                if(isset($data['logo'])){
                    FileHelper::removeFiles([$company->logo]);
                    $data['logo'] = FileHelper::uploadFiles([$data['logo']] ,"companies/logo")[0];
                }else{
                    $data['logo'] = $company->logo;
                }
                //--------------------
                //update
                $this->companiesRepo->update($data,$company->id );
                //update related data phones, emails,...
                $phones_rules?$this->companiesRepo->updateManyRelated($company,$data['phones'], "phones"):null;
                $mobiles = $mobiles_rules?$this->companiesRepo->updateManyRelated($company,$data['mobiles'], "mobiles"):null;
                $this->companiesRepo->syncSocials("mobiles" , $mobiles, $data['mobiles']??[]);
                $emails = $emails_rules? $this->companiesRepo->updateManyRelated($company,$data['emails'], "emails"):null;
                $this->companiesRepo->syncSocials("emails" , $emails, $data['emails']??[]);
               // $addresses_rules? $this->companiesRepo->updateManyRelated($company,$data['addresses'], "addresses"):null;
                $notes_rules? $this->companiesRepo->updateManyRelated($company,$data['notes'], "notes"):null;
                $faxes_rules? $this->companiesRepo->updateManyRelated($company,$data['faxes'], "faxes"):null;
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
            }
            return JsonResponse::respondError($company_validator->errors()->all());
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
           $company = $this->companiesRepo->find($id);
            FileHelper::removeFiles([$company->logo]);
            $this->companiesRepo->delete($company);
            $this->companiesRepo->deleteRelated($company, ["phones","mobiles","faxes","positions","addresses","notes"]);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        } catch (\Exception $ex) {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    public function upload_files($request , $data)
    {
        if($request->file('files'))
        {
          $data['files'] = $request->file('files');
          return FileHelper::uploadFiles($data['files'] ,"companies/files");
        }
        return null;
    }

    /**
     * @param Request $request
     * @param $data
     * @return array
     */
    public function createFiles(Request $request, $data){
        $ids = [];
        if(isset($data['files']))
        {
            $files = $this->upload_files($request, $data);
            foreach($files as $file) {
                $f = $this->fileRepo->create(['path' => $file]);
                array_push($ids, $f->id);
            }
        }

        return $ids;
    }

    private function getRules($data)
    {
        $phones_rules = !empty($data['phones'])?
            CiPhone::PHONE_RULES+["phones.*.number"=>['required',
                new PhoneLength($data["phones"]),
                new PhonePrefixes($data["phones"])]]:[];
        $mobiles_rules = !empty($data['mobiles'])?
            CiMobile::MOBILE_RULES+["mobiles.*.number"=>['required',
                new NumberLength($data["mobiles"]),
                new NumberPrefix($data["mobiles"])]]:[];
        $faxes_rules = !empty($data['faxes'])?
            CiFax::FAX_RULES+["faxes.*.number"=>['required',
                new PhoneLength($data["faxes"]),
                new PhonePrefixes($data["faxes"])]]:[];
        $emails_rules = !empty($data['emails'])?CiEmail::EMAIL_RULES:[];
        $notes_rules = !empty($data['notes'])?CiNote::NOTE_RULES:[];
        $addresses_rules = !empty($data['addresses'])?CiAddress::CREATE_UPDATE_RULES:[];
        $location_rules = !empty($data['location'])?CiPosition::CREATE_UPDATE_RULES:[];

        return  Company::CREATE_RULES+$phones_rules+$mobiles_rules+$emails_rules+$notes_rules+$faxes_rules+$addresses_rules+$location_rules;
    }
}
