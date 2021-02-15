<?php


namespace Helpers;


class ResponseStatus
{
    const NOT_FOUND = 404;
    const NOT_ALLOWED= 405 ;
    const NOT_AUTHORIZED = 401;
    const ACCESS_FORBIDDEN = 403;
    const BAD_REQUEST = 400;
    const VALIDATION_ERROR = 422;
    const GENERAL_ERROR = 500;
    const CREATED = 201;
    const SUCCESS = 200;

    /**
     * @return array
     * @desc this function is return all success response status
     * you may use it to check if this array contains any response status
     * @example
     * dd(in_arra(200 , ResponseStatus::getSuccessStatus()));
     * @author karam mustafa
     */
    public static function getSuccessStatus(){
        return [200 , 201];
    }
}
