<?php


namespace Helpers;

use Exception;
use Illuminate\Support\Facades\DB;

class BaseQueryHelper
{
    /**
     * @param $options
     * @param $withOperation
     * @return array
     * @throws Exception
     * @author karam mustafa
     */
    public static function buildQuery($options, $withOperation = true)
    {
        try {
            // this will contains each id and new value
            $cases = [];
            // ids to update
            $ids = [];
            // column in table to effect them by process
            $col = $options['cols'];
            foreach ($options['arrayOfRecords'] as $record) {
                // this contains which columns we want to update
                // implement this add or minus or whatever this logic
                if ($withOperation) {
                    $process = "return " . ($record->$col . " " . $options['operation'] . " " . ($options['value'])) . ";";
                    $cases[] = "WHEN {$record->id} then " . eval($process);
                } else {
                    $cases[] = "WHEN {$record->id} then '{$options['value']}'";
                }
                $ids[] = $record->id;
            }
            return ['cases' => $cases, 'ids' => $ids];
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @param mixed $model
     * @param mixed $tableName
     * @param mixed $uniqueColumns
     * @param array $array
     *
     * @return void
     *
     * @auth yasser kanj
     */
    public static function syncWithData($model,$tableName, $uniqueColumns,$array = []){
        collect($array)->map(function($v, $k)
        use ($model,$tableName, $uniqueColumns){
            $v[$uniqueColumns[0]] = $model->id;
            $decision = DB::table($tableName)
                            ->where($uniqueColumns[0],$v[$uniqueColumns[0]])
                            ->where($uniqueColumns[1],$v[$uniqueColumns[1]])
                            ->get();
            if(count($decision) > 0)
                DB::table($tableName)
                    ->where($uniqueColumns[0],$v[$uniqueColumns[0]])
                    ->where($uniqueColumns[1],$v[$uniqueColumns[1]])
                    ->update($v);
            else
                DB::table($tableName)->insert($v);
        });
    }

}
