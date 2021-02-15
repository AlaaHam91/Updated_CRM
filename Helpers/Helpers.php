<?php


namespace Helpers;


use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Array_;
use function PHPSTORM_META\map;

class Helpers
{
    function toCamel($o)
    {
        if (is_array($o)) {
            return array_map(function ($value) {
                if (is_object($value)) {
                    $value = $this->toCamel($value);
                }
                return $value;
            }, $o);
        } else {
            $newO = [];
            foreach ($o as $origKey) {
                if (array_key_exists($origKey, $o)) {
                    $newKey = Str::camel($origKey);
                    $value = $o[$origKey];
                    if (is_array($value) || ($value !== null && is_object($value))) {
                        $value = $this->toCamel($value);
                    }
                    $newO[$newKey] = $value;
                }
            }
        }
        return $newO;
    }

    /**
     * @param mixed $tableName
     * @param mixed $col
     *
     * @return mixed
     * @auth yasser kanj
     */
    public static function generateUniqueCode($tableName, $col){
        $code = hexdec(uniqid(true));
        $result = \DB::table($tableName)->where($col,$code)->exists();
        if ($result){
            return self::generateUniqueCode($tableName, $col);
        }
        return $code;
    }
}
