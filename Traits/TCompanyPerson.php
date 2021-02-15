<?php
namespace Traits;
use App\Http\Repositories\IRepositories\IContactRepository;
use App\Http\Repositories\IRepositories\IPersonRepository;
use App\Models\CiAddress;
use App\Models\CiEmail;
use App\Models\CiFax;
use App\Models\CiMobile;
use App\Models\CiNote;
use App\Models\CiPhone;
use App\Models\CiPosition;
use App\Models\CompanyPerson;
use App\Models\Person;
use App\Rules\NumberLength;
use App\Rules\NumberPrefix;
use App\Rules\PhoneLength;
use App\Rules\PhonePrefixes;
use Helpers\FileHelper;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Repositories\IRepositories\ICompanyPeopleRepository;

/**
 * Trait TCompanyPerson
 * @package Traits
 * @author Kamal Mohammed
 */
trait TCompanyPerson {
    /**
     * @var IPersonRepository
     */
    protected $personRepo;
    /**
     * @var IContactRepository
     */
    protected $contactRepo;
    /**
     * @var ICompanyPeopleRepository
     */
    protected $cPersonRepo;
    /**
     * @var array
     */
    protected $requestData;

    /**
     * TCompanyPerson constructor.
     * @param IPersonRepository $pr
     * @param ICompanyPeopleRepository $cpr
     * @param IContactRepository $cn
     */
    public function __construct(
        IPersonRepository $pr,
        ICompanyPeopleRepository $cpr,
        IContactRepository $cn
    )
    {
        $this->personRepo = $pr;
        $this->cPersonRepo = $cpr;
        $this->contactRepo = $cn;
        $this->requestData = Mapper::toUnderScore(\Request()->all());
    }
   /**
    * preparing validation before inserting or updating
    * @return mixed
    * @param $category
    * @param $data
    */
    public static function prepareValidation($category , $data)
    {
        $rules = [];
        switch($category)
        {
            case "phones":
                $rules = !empty($data['person']["phones"])?
                    CiPhone::PHONE_RULES+["phones.*.number"=>['required',
                        new PhoneLength($data["person"]["phones"]),
                        new PhonePrefixes($data["person"]["phones"])]]:[];
                break;
            case "mobiles":
                $rules = !empty($data['person']['mobiles'])?
                    CiMobile::MOBILE_RULES+["mobiles.*.number"=>['required',
                        new NumberLength($data["person"]["mobiles"]),
                        new NumberPrefix($data["person"]["mobiles"])]]:[];
                break;
            case "faxes":
                $rules =!empty($data['person']['faxes'])?
                    CiFax::FAX_RULES+["faxes.*.number"=>['required',
                        new PhoneLength($data["person"]["faxes"]),
                        new PhonePrefixes($data["person"]["faxes"])]]:[];
                break;
            case "emails":
                $rules = !empty($data['person']['emails'])?CiEmail::EMAIL_RULES:[];
                break;
            case "notes":
                $rules = !empty($data['person']['notes'])?CiNote::NOTE_RULES:[];
                break;
            case "location":
                $rules = !empty($data['person']['location'])?CiPosition::NOTE_RULES:[];
                break;
            case "address":
                $rules = !empty($data['person']['address'])?CiAddress::CREATE_UPDATE_RULES:[];
                break;
            case "cperson":
                $rules = !empty($data['person']['cperson'])?CompanyPerson::CREATE_UPDATE_RULES:[];
                break;
            case "person":
                $rules = !empty($data['person'])?Person::CREATE_UPDATE_RULES:[];
                break;
            default:
                $rules = [];
                break;
        }
        return $rules;
    }

    /**
     * @param CompanyPerson $company_person
     * @param $data
     */
    public function createCpersonRelated(CompanyPerson  $company_person , $data)
    {
        $this->cPersonRepo->createManyRelated($company_person,$data['person']['phones']??[],"phones");
        $this->cPersonRepo->createManyRelated($company_person,$data['person']['notes']??[],"notes");
        $this->cPersonRepo->createManyRelated($company_person,$data['person']['faxes']??[],"faxes");
        $mobiles = $this->cPersonRepo->createManyRelated($company_person,$data['person']['mobiles']??[],"mobiles");
        $emails = $this->cPersonRepo->createManyRelated($company_person,$data['person']['emails']??[],"emails");
        //create related social media sites..
        $this->cPersonRepo->syncSocials("emails" , $mobiles, $data['person']['emails']??[]);
        $this->cPersonRepo->syncSocials("mobiles" , $emails, $data['person']['mobiles']??[]);
    }

    /**
     * @param $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function doValidation($data)
    {

        $person_rules  = self::prepareValidation("person" , $data);
        $cperson_rules = self::prepareValidation("cperson" , $data);
        $phones_rules  = self::prepareValidation("phones" , $data);
        $mobiles_rules = self::prepareValidation("mobiles" , $data);
        $faxes_rules   = self::prepareValidation("faxes" , $data);
        $emails_rules  = self::prepareValidation("emails" , $data);
        $notes_rules   = self::prepareValidation("notes" , $data);
        $addresses_rules = self::prepareValidation("address" , $data);
        $location = self::prepareValidation("location" , $data);

        $validation_rules = $person_rules+$cperson_rules+$phones_rules+$mobiles_rules+$emails_rules+$notes_rules+$faxes_rules;

        return Validator::make($data['person'],$validation_rules,ValidatorHelper::messages());

    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function createNewCompanyPerson($data, $relation="contact")
    {
        //$data = $this->requestData;
        $validator = self::doValidation($data);
        if($validator->passes())
        {
            if(isset($data['person']['image'])){
                $data['person']['image'] = FileHelper::uploadFiles([$data['person']['image']] ,"people/avatars")[0];
            }
            DB::beginTransaction();
            //create person
            $person = $this->personRepo->create($data['person']);
            //create company person
            $company_person = $this->personRepo->createCompanyPerson($person, $data['person']['cperson']);
            //create company person related contacts info
            $this->createCpersonRelated($company_person , $data);
            $contact = $this->cPersonRepo->createRelatedLife($company_person , $relation);

            if($relation === "agent")
            {
               $this->contactRepo->syncRelated($contact, $data['person']['products'],"agent");
               $this->contactRepo->syncRelated($contact, $data['person']['divisions'],"agent");
               $this->contactRepo->createManyRelated($contact , $data['person']['contracts'] , "agent");
            }
            isset($data['person']['life'])?$life    = $this->contactRepo->createRelated($contact , $data['person']['life']):[];
            isset($data['person']['life']['activities'])?$this->contactRepo->synActivities($life, $data['person']['life']['activities']):[];
            isset($data['person']['life']['interests'])?$this->contactRepo->synInterests($life, $data['person']['life']['interests']):[];
            DB::commit();
            $company_person->load('person',$relation);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$company_person);
        }
        else
        {
            return JsonResponse::respondError($validator->errors()->all());
        }
    }

    /**
     * @param $data
     * @param $cid
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOthersOrAddType($data , $cid, $relation="")
    {
        $validator = self::doValidation($data);
        if($validator->passes())
        {
         $company_person = $this->cPersonRepo->find($cid);
         if(isset($company_person->id))
         {
             $pid = $company_person->person_id;
             #Remove The oldest image
             $person = $this->personRepo->find($pid);
             FileHelper::removeFiles(isset($person->image)?$person->image:"");
             #Upload New image
             if(isset($data['person']['image']))
             {
              $data['person']['image'] = FileHelper::uploadFiles([$data['person']['image']] ,"people/avatars")[0];
             }
             $this->personRepo->update($data['person'], $pid);
             $this->personRepo->update($data['person']['cperson'], $cid);
             $this->createCpersonRelated($company_person , $data);
             $contact = $company_person->{$relation};
            if(!isset($contact->id))
                 $contact = $this->cPersonRepo->createRelatedLife($company_person , $relation);
             $life = $contact->cpersonLife;
             if(!isset($life->id))
                $life    = $this->contactRepo->createRelated($contact , $data['person']['life']);
             $this->contactRepo->synActivities($life, $data['person']['life']['activities']);
             $this->contactRepo->synInterests($life, $data['person']['life']['interests']);

             if($relation === "agent")
             {
                 $this->contactRepo->syncRelated($contact, $data['person']['products'],"agent");
                 $this->contactRepo->syncRelated($contact, $data['person']['divisions'],"agent");
                 $this->contactRepo->createManyRelated($contact , $data['person']['contracts'] , "agent");
             }

             $company_person->load('person',$relation);
             DB::commit();
             return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$company_person);
         }
         else
         {
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_NOT_FOUND));
         }
        }
        else
        {
            return JsonResponse::respondError($validator->errors()->all());
        }
    }
}


