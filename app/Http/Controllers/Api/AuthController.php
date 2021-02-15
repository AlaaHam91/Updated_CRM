<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\IRepositories\IUserRepository;
use App\Models\CiAddress;
use App\Models\CiEmail;
use App\Models\CiFax;
use App\Models\CiMobile;
use App\Models\CiNote;
use App\Models\CiPhone;
use App\Models\CiPosition;
use App\Models\User;
use App\Rules\NumberLength;
use App\Rules\NumberPrefix;
use App\Rules\PhoneLength;
use App\Rules\PhonePrefixes;
use Helpers\FileHelper;
use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\ValidatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Class AuthController
 * @package App\Http\Controllers\Api
 * @author Kamal Mohammed
 */
class AuthController extends Controller
{

    /**
     * @var array
     */
    private $requestData;
    /**
     * @var IUserRepository
     */
    private $userRepository;

    /**
     * AuthController constructor.
     * @param IUserRepository $userRepository
     */
    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->requestData = Mapper::toUnderScore(\Request()->all());
    }
    public function index()
    {
        try
        {
          $models = $this->userRepository->all();
          return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$models);
        }
        catch(\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try{
            $data = $this->requestData;

            if(request('image')){
                $data['image'] = $request->file('image');
            }
            $validator = Validator::make($data, $this->validationRules($data),ValidatorHelper::messages());
            if($validator->passes())
            {
                if(isset($data['image'])){
                    $data['image'] = FileHelper::uploadFiles([$data['image']] ,"users/avatars")[0];
                }

                $data['password'] = bcrypt($data['password']);
                $user = User::create($data);
                $success['token'] =  $user->createToken('crm')->accessToken;
                $success['name'] =  $user->name;

                isset($data['mobiles'])?$this->createRelated($user, "mobiles",$data['mobiles']):"";

                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY),$success);
            }
            else
            {
                return JsonResponse::respondError($validator->errors()->all());
            }
        }
        catch(\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        try
        {
            $validator = Validator::make($request->all(), ['email'=>'required|email','password'=>'required']);

            if($validator->passes())
            {
                if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                    $user = Auth::user();
                    $success['token'] =  $user->createToken('crm')-> accessToken;
                    $success['name'] =  $user->name;
                    return JsonResponse::respondSuccess(trans(JsonResponse::MSG_LOGIN_SUCCESSFULLY),$success);
                }
                else
                {
                    return JsonResponse::respondError(trans(JsonResponse::MSG_LOGIN_FAILED));
                }

            }
            else
            {
                return JsonResponse::respondError($validator->errors()->all());
            }
        }
        catch(\Exception $ex)
        {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request) {
      try{
          auth()->user()->tokens->each(function($token, $key){
              $token->delete();
          });

          return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS));
      }
      catch(\Exception $ex)
      {
         return JsonResponse::respondError($ex->getMessage());
      }
    }

    /**
     * @param $data
     * @return array|string[]
     */
    private function validationRules($data)
    {
        $user_rules = User::CREATE_UPDATE_RULES;
        $mobile_rules = isset($data['mobiles'])?
                CiMobile::MOBILE_RULES+["mobiles.*.number"=>['required',
                new NumberLength($data["mobiles"]),
                new NumberPrefix($data["mobiles"])]]:[];
        $phone_rules = isset($data['phones'])?
                CiPhone::PHONE_RULES+["phones.*.number"=>['required',
                new PhoneLength($data["phones"]),
                new PhonePrefixes($data["phones"])]]:[];
        $fax_rules = isset($data['faxes'])?CiFax::FAX_RULES+["faxes.*.number"=>['required',
                new PhoneLength($data["faxes"]),
                new PhonePrefixes($data["faxes"])]]:[];
        $email_rules = isset($data['emails'])?CiEmail::EMAIL_RULES:[];
        $address_rules = isset($data['addresses'])?CiAddress::CREATE_UPDATE_RULES:[];
        $note_rules = isset($data['notes'])?CiNote::NOTE_RULES:[];
        $location_rules = isset($data['locations'])?CiPosition::CREATE_UPDATE_RULES:[];
        return  $user_rules+$mobile_rules+$phone_rules+$fax_rules+$email_rules+$address_rules+$note_rules+$location_rules;
    }

    private function  createRelated($model , $relation, $data)
    {
        return $model->{$relation}()->createMany($data);
    }
}
