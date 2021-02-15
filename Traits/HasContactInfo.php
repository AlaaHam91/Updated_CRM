<?php


namespace Traits;


use App\Models\CiAddress;
use App\Models\CiEmail;
use App\Models\CiFax;
use App\Models\CiMobile;
use App\Models\CiNote;
use App\Models\CiPhone;
use App\Models\CiPosition;
use App\Rules\NumberLength;
use App\Rules\NumberPrefix;
use App\Rules\PhoneLength;
use App\Rules\PhonePrefixes;

/**
 * Trait HasContactInfo
 * @package Traits
 * @author Kamal Mohammed
 */
trait HasContactInfo
{
    public static function prepare($category, $key , $data)
    {
        switch($category)
        {
            case "phones":
                return !empty($data[$key]["phones"])?
                    CiPhone::PHONE_RULES+["phones.*.number"=>['required',
                        new PhoneLength($data[$key]["phones"]),
                        new PhonePrefixes($data[$key]["phones"])]]:[];
                break;
            case "mobiles":
                return !empty($data[$key]['mobiles'])?
                    CiMobile::MOBILE_RULES+["mobiles.*.number"=>['required',
                        new NumberLength($data[$key]["mobiles"]),
                        new NumberPrefix($data[$key]["mobiles"])]]:[];
                break;
            case "faxes":
                return !empty($data[$key]['faxes'])?
                    CiFax::FAX_RULES+["faxes.*.number"=>['required',
                        new PhoneLength($data[$key]["faxes"]),
                        new PhonePrefixes($data[$key]["faxes"])]]:[];
                break;
            case "emails":
                return !empty($data[$key]['emails'])?CiEmail::EMAIL_RULES:[];
                break;
            case "notes":
                return !empty($data[$key]['notes'])?CiNote::NOTE_RULES:[];
                break;
            case "location":
                return !empty($data[$key]['location'])?CiPosition::CREATE_UPDATE_RULES:[];
                break;
            case "address":
                return !empty($data[$key]['address'])?CiAddress::CREATE_UPDATE_RULES:[];
                break;
            default:
                return [];
                break;
        }
    }

}
