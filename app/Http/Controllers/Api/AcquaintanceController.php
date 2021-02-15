<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\IAcquaintanceRepository;
use App\Http\Repositories\IRepositories\IPersonRepository;
use App\Models\Acquaintance;
use App\Models\Person;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Traits\HasContactInfo;

/**
 * Class AcquaintanceController
 * @package App\Http\Controllers\Api
 * @author Kamal Mohammed
 */
class AcquaintanceController extends Controller
{
    use HasContactInfo;

    /**
     * @var IPersonRepository
     */
    protected $personRepo;
    /**
     * @var IAcquaintanceRepository
     */
    protected $acquaintanceRepo;
    /**
     * @var array
     */
    protected $requestData;

    /**
     * AcquaintanceController constructor.
     * @param IPersonRepository $prepo
     * @param IAcquaintanceRepository $arepo
     */
    public function __construct(
        IPersonRepository $prepo,IAcquaintanceRepository $arepo
    )
    {
        $this->personRepo = $prepo;
        $this->acquaintanceRepo = $arepo;
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
            $models = $this->acquaintanceRepo->all();
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
       // try
      //  {

            $rules = self::doValidation($data);
            $validation = Validator::make($data['person'],$rules,ValidatorHelper::messages());
           if($validation->passes())
           {
               DB::beginTransaction();
                    $p = $this->personRepo->create($data['person']);
                    $acq = $this->personRepo->createAcquaintance($p, $data['person']['acquaintance']);
                    $mobiles = isset($data['person']['phones'])?$this->acquaintanceRepo->createManyRelated($acq,$data['person']['phones'],"phones"):"";
                    isset($data['person']['mobiles'])?$this->acquaintanceRepo->createManyRelated($acq,$data['person']['mobiles'],"mobiles"):"";
                    isset($data['person']['faxes'])?$this->acquaintanceRepo->createManyRelated($acq,$data['person']['faxes'],"faxes"):"";
                    $emails = isset($data['person']['emails'])?$this->acquaintanceRepo->createManyRelated($acq,$data['person']['emails'],"emails"):"";
                    isset($data['person']['location'])?$this->acquaintanceRepo->createManyRelated($acq,$data['person']['location'],"positions"):"";
                    isset($data['person']['notes'])?$this->acquaintanceRepo->createManyRelated($acq,$data['person']['notes'],"notes"):"";
                    if(isset($mobiles))
                        $this->acquaintanceRepo->syncSocials("mobiles" , $mobiles, $data['person']['mobiles']??[]);
                    if(isset($emails))
                        $this->acquaintanceRepo->syncSocials("emails" , $mobiles, $data['person']['emails']??[]);
                    isset($data['person']['sharing'])?$this->acquaintanceRepo->syncRelated($acq,'sharedUsers',$data['person']['sharing']):"";

               DB::commit();
             $p->load('acquaintance');
             return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY),$p,201);
           }
           else
           {
               DB::rollBack();
               return JsonResponse::respondError($validation->errors()->all());
           }
       /* }
        catch(\Exception $ex)
        {
            DB::rollBack();
            return JsonResponse::respondError($ex->getMessage());
        }*/
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try
        {
            $models = $this->acquaintanceRepo->find($id);
            $models->load('person');
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
        try{
            $aq = $this->acquaintanceRepo->find($id);
            $rules = self::doValidation($data);
            $validation_rules['person']['person_id'] = "required|".Rule::unique('persons','id')->ignore($aq->person_id);
            $validation_rules['person']['acquaintance']['id'] = "required|".Rule::unique('acquaintances','id')->ignore($aq->id);
            $validation = Validator::make($data['person'],$rules,ValidatorHelper::messages());
            if($validation->passes())
            {
                DB::beginTransaction();
                $this->personRepo->update($data['person'],$aq->person_id??null);
                $this->acquaintanceRepo->update($data['person']['acquaintance'],$id);
                $mobiles = isset($data['person']['phones'])?$this->acquaintanceRepo->updateManyRelated($aq,$data['person']['phones'],"phones"):"";
                isset($data['person']['mobiles'])?$this->acquaintanceRepo->updateManyRelated($aq,$data['person']['mobiles'],"mobiles"):"";
                isset($data['person']['faxes'])?$this->acquaintanceRepo->updateManyRelated($aq,$data['person']['faxes'],"faxes"):"";
                $emails = isset($data['person']['emails'])?$this->acquaintanceRepo->updateManyRelated($aq,$data['person']['emails'],"emails"):"";
                isset($data['person']['location'])?$this->acquaintanceRepo->updateManyRelated($aq,$data['person']['location'],"positions"):"";
                isset($data['person']['notes'])?$this->acquaintanceRepo->updateManyRelated($aq,$data['person']['notes'],"notes"):"";
                if(isset($mobiles))
                    $this->acquaintanceRepo->syncSocials("mobiles" , $mobiles, $data['person']['mobiles']??[]);
                if(isset($emails))
                    $this->acquaintanceRepo->syncSocials("emails" , $mobiles, $data['person']['emails']??[]);
                isset($data['person']['sharing'])?$this->acquaintanceRepo->syncRelated($aq,'sharedUsers',$data['person']['sharing']):"";
                DB::commit();
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
            }
            else
            {
                return JsonResponse::respondError($validation->errors()->all());
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
        try {
            $model = $this->acquaintanceRepo->find($id);
            $this->personRepo->delete($this->personRepo->find($model->person_id));
            $this->acquaintanceRepo->delete($model);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        }
        catch(\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    /**
     * @param $data
     * @return array|array[]|string[]
     */
    public static function  doValidation($data)
    {
        $acq_rules = isset($data['person']['acquaintance'])?Acquaintance::CREATE_UPDATE_RULES:[];
        $person_rules = isset($data['person'])?Person::CREATE_UPDATE_RULES:[];
        $phones   = self::prepare("phones","person", $data);
        $mobiles  = self::prepare("mobiles","person", $data);
        $faxes    = self::prepare("faxes","person", $data);
        $emails   = self::prepare("emails","person", $data);
        $location = self::prepare("location","person", $data);
        $address  = self::prepare("address","person", $data);
        $notes    = self::prepare("notes","person", $data);

        return $person_rules+$acq_rules+$phones+$mobiles+$faxes+$emails+$location+$address+$notes;

    }
}
