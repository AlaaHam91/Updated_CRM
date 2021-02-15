<?php


namespace Helpers;


use Closure;
use Exception;
use Illuminate\Support\Facades\DB;

class ValidatorHelper
{
    public static $exitsValidationErrors = [];

    /**
     * @return array
     */
    public static function messages()
    {
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        return $messages;
    }

    /**
     * @param $request
     * @param $options
     * @param Closure $callback
     * @throws Exception
     */
    public static function validateExistsRule($request, $options , Closure $callback = null)
    {
        try {
            // if you want to implement any custom process in your request
            $request = $callback != null ?  $callback($request) : $request;
            // collapse this property
            $col = collect($request)->collapse()->toArray();

            foreach ($options as $requestedKey => $option) {
                // get the key from all request property
                $requestKeysValues = array_column($col, $requestedKey);
                // get ids
                $tableValue = DB::table($option['table'])->pluck($option['column'] ?? 'id')->toArray();
                // we will check if this table contains this ids
                foreach ($requestKeysValues as $item) {
                    if (!in_array($item, $tableValue)) {
                        // check if validation options contains keys
                        // default value of exists validation rule
                        $validation = isset($option['validation']) ? $option['validation'] : ['exists', 'sometimes'];
                        if (in_array('exists', $validation)) {
                            self::buildErrorMessage($requestedKey, $item);
                        } //  end if
                    } //  end if
                } //  end foreach
            }// end foreach
            // this mean we have an error
            if (sizeof(self::$exitsValidationErrors) > 0) {
                throw new Exception(collect(self::$exitsValidationErrors));
            }// end if
        } catch (Exception $exception) {
            throw new Exception(json_encode(JsonResponse::formatExceptionMessage($exception)));
        }// end catch
    }// end function

    public static function buildErrorMessage($requestedKey, $item)
    {
        // check if item is null we will put 'null'
        $value = $item == null ? 'null' : $item;
        array_push(self::$exitsValidationErrors, "the selected $requestedKey at value '$value' is invalid");
    }// end function
}
