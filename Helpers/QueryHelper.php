<?php


namespace Helpers;


use Exception;
use Illuminate\Support\Facades\DB;

class QueryHelper extends BaseQueryHelper
{

    /**
     * @param array $options
     * @param bool $withOperation
     * @return bool
     * @throws Exception
     * @desc this function will update multi record in one query
     * @example fot this query
     * UPDATE acards //  table name
     *   SET unposted_balance_debit // colmun name = CASE
     *                   WHEN id // id for the record = 3 THEN 500 // new value to insert
     *                   WHEN id // id for the record = 12 THEN 90 // new value to insert
     *               END
     *   WHERE ID IN (3, 12) //  ids we want to update at once
     *
     * @author karam mustafa
     */
    public static  function updateMultiRecordAtOnce($options = [] , $withOperation = true)
    {
        try {
            $query = self::buildQuery($options , $withOperation);
            // we will implode this values to prepare them to query syntax
            [$query['ids'], $query['cases']] = [implode(',', $query['ids']), implode(' ', $query['cases'])];
            if (!empty($query['ids'])) {
                DB::statement("UPDATE {$options['table']} SET `{$options['cols']}` = CASE `id` {$query['cases']} END WHERE `id` in ({$query['ids']})");
            }
            return true;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
