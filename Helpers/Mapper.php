<?php


namespace Helpers;

use Illuminate\Support\Str;


class Mapper
{
    public static function snakeToCamel($key)
    {
        // replace underscores with spaces, uppercase first letter of all words,
        // join them, lowercase the very first letter of the name
        if (isset($key))
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
    }

    /**
     * @param $model
     * @return mixed
     * @description map between model attributes and model variables
     */
    public static function setModelProperties($model)
    {

        $attributes = $model->getAttributes();
        foreach (get_object_vars($model) as $key_name => $value) {
            $method_name = "set" . ucfirst($key_name);
            if (method_exists($model, $method_name)) {
                $model->{$method_name}($value);
            } else {
                if (isset($attributes[$key_name]))
                    $model->{$key_name} = $attributes[$key_name];
            }
        }
        return $model;
    }

    public static function camelToUnderscore($string, $us = "_")
    {
        return strtolower(preg_replace(
            '/(?<=\d)(?=[A-Za-z])|(?<=[A-Za-z])(?=\d)|(?<=[a-z])(?=[A-Z])/', $us, $string));
    }
    public static function camelToUnderscoreArray($array, $us = "_")
    {
        $underscored = [];
        if (isset($array) && is_array($array))
        foreach ($array as $key =>$value){
            $underscored[strtolower(preg_replace(
                '/(?<=\d)(?=[A-Za-z])|(?<=[A-Za-z])(?=\d)|(?<=[a-z])(?=[A-Z])/', $us, $key))]=$value;

        }
        return $underscored;

    }

    public static function camelToSnake($string, $us = "_")
    {
        return strtolower(preg_replace(
            '/(?<=\d)(?=[A-Za-z])|(?<=[A-Za-z])(?=\d)|(?<=[a-z])(?=[A-Z])/', $us, $string));
    }

    /**
     * @param $o
     * @return array
     * @desc convert object of json array to camelCase
     * @author  ali shaaban
     */
    public static function toCamel($o)
    {
        $o = json_decode(json_encode($o));
        if (is_array($o)) {
            return array_map(function ($value) {
                if (is_object($value)) {
                    $value = Mapper::toCamel($value);
                }
                return $value;
            }, $o);
        } else {
            $newO = [];
            foreach ($o as $origKey => $val) {
                    $newKey = Str::camel($origKey);
                    $value = $val;
                    if (is_array($value) || ($value !== null && is_object($value))) {
                        $value = Mapper::toCamel($value);
                    }
                    $newO[$newKey] = $value;
            }
        }
        return $newO;
    }
    /**
     * @param $o array to convert
     * @return array
     * @desc convert object of json array to underScore
     * @author ali shaaban
     */
    public static function toUnderScore($o)
    {
        $o = json_decode(json_encode($o));
        if (is_array($o)) {
            return array_map(function ($value) {
                if (is_object($value)) {
                    $value = Mapper::toUnderScore($value);
                }
                return $value;
            }, $o);
        } else {
            $newO = [];
            foreach ($o as $origKey => $val) {
                $newKey = Str::snake($origKey);
                $value = $val;
                if (is_array($value) || ($value !== null && is_object($value))) {
                    $value = Mapper::toUnderScore($value);
                }
                $newO[$newKey] = $value;
            }
        }
        return $newO;
    }
}
