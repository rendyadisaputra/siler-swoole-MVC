<?php

namespace App\Models;

use App\Root;

/*!
 *  \brief     This class is the Parents of all models that related to Mongo models <br/>
 * You can use it by just extends it please see the example below
 *  \author    The Code, CLS IT Departement
 *  \version   2.0
 *  \date      2020
 *  \copyright Ⓒ The Code 2020
 *
 * This class is the Parents of all models that related to Mongo models <br/>
 *
 * You can use it by just extends it
 *
 * Example:
 * ```
 * class ExampleClassModel extends Mongomodel{
 *
 *  public $table = 'Lo_card.Banner'; //Your Table Name is Required
 *      function yourFunction(){
 *          //.... your code here ...
 *      }
 *
 * }
 * ```
 */
use function App\Functions\getGlobalVar;
use function App\Functions\setGlobalVar;


class MongoModel extends Root
{
    public $MongoClient = false;
    public $error = false;
    public $error_code = 0;
    public $requestColumns = false;
    public $requestColumnsInArray = [];
    public $selectedDB = 'chitatoz';

    private $currentUser = false;

    public static function __callStatic($name, $arguments)
    {
        return (new  MongoModel())->$name()->init();
    }

    public function __construct()
    {
        $MongoClient = getGlobalVar('MongoClient');
        if(is_null($MongoClient)){
            $MongoClient = new \MongoDB\Driver\Manager(MONGODB_HOST);
            setGlobalVar('MongoClient', $MongoClient);
        }
        // $this->MongoClient = new \MongoDB\Driver\Manager(MONGODB_HOST);

        return parent::__construct(__DIR__);
    }

    /*!
     * Sending the Result to an Array result
     * @param  Array $result Even your result can be return it as __String__ I will not recommend it. Please send the result in __Array__ instead.
     * \return ```['result'=>$result,'error'=>false]```
     */

    public function sendResult($result = '')
    {
        if ($this->error !== false) {
            // write_log([
            //         'error' => $this->error,
            //         'error_code' => $this->error_code,
            //     ], 'ERROR');

            $returnError = [
                    'error' => $this->error,
                    'error_code' => $this->error_code,
                ];

            throw new \Exception($returnError['error'], $returnError['error_code']);
        } else {
            return [
                    'result' => $result,
                    'error' => false,
                ];
        }
    }

    // public function insertBatch($data)
    // {
    //     if ($this->error !== false) {
    //         return $this->sendResult();
    //     }

    //     try {
    //         $bulk = new \MongoDB\Driver\BulkWrite();
    //         $id = [];

    //         $dateNow = date('Y-m-d H:i:s');
    //         $dateNow = $this->convertToMongoDateTime($dateNow);
    //         foreach ($data as $key => $value) {
    //             $value['created_date'] = $dateNow;
    //             $value['updated_date'] = $dateNow;
    //             if (!empty($this->currentUser)) {
    //                 $value['created_by'] = $this->currentUser;
    //                 $value['updated_by'] = $this->currentUser;
    //             }
    //             $id[$key] = $bulk->insert($value);
    //         }
    //         $result = $this->MongoClient->executeBulkWrite($this->table, $bulk);
    //         $dbRes = [
    //                     'status' => 'success',
    //                     '_id' => $id,
    //                 ];
    //     } catch( \Exception $e) {
    //         return $this->sendError($e->getMessage(), 500);
    //     }

    //     return $this->sendResult($dbRes);
    // }

    // public function updateBatch($data)
    // {
    //     if ($this->error !== false) {
    //         return $this->sendResult();
    //     }

    //     try {
    //         $bulk = new \MongoDB\Driver\BulkWrite();
    //         $id = [];

    //         $ipAddr = getClientIpAddr();
    //         $dateNow = date('Y-m-d H:i:s');
    //         $dateNow = $this->convertToMongoDateTime($dateNow);

    //         foreach ($data as $key => $value) {
    //             empty($value['filter']) ?
    //                 $this->sendError('Update Query is required', 500) :
    //                 $filter = $value['filter'];
    //             empty($value['new_value']) ?
    //                 $this->sendError('Update Value is required', 500) :
    //                 $newValue = $value['new_value'];

    //             $option = !empty($value['option']) ? $value['option'] : [];
    //             $option['multi'] = !isset($option['multi']) ? true : (($option['multi'] == true) ? true : false);
    //             $option['upsert'] = !isset($option['upsert']) ? true : (($option['upsert'] == true) ? true : false);

    //             $custom = empty($value['custom']) ? false : true;

    //             if (isset($newValue['_id'])) {
    //                 unset($newValue['_id']);
    //             }

    //             if (!$custom) {
    //                 $newValue['released_date'] = $dateNow;
    //                 $newValue['updated_date'] = $dateNow;
    //                 if (!empty($this->currentUser)) {
    //                     $newValue['updated_by'] = $this->currentUser;
    //                 }
    //                 $newValue['updated_at'] = $ipAddr;

    //                 $id[$key] = $bulk->update($filter, ['$set' => $newValue], $option);
    //             } else {
    //                 $id[$key] = $bulk->update($filter, $newValue, $option);
    //             }
    //         }

    //         $result = $this->MongoClient->executeBulkWrite($this->table, $bulk);
    //         $dbRes = [
    //                     'status' => 'success',
    //                     '_id' => $id,
    //                 ];
    //     } catch( \Exception $e) {
    //         return $this->sendError($e->getMessage(), 500);
    //     }

    //     return $this->sendResult($dbRes);
    // }

    public function DBinsert($data)
    {
        if ($this->error !== false) {
            return $this->sendResult();
        }

        // var_dump("exit", );
        $ipAddr = \Siler\Swoole\request()->server['remote_addr'];

        try {
            $bulk = new \MongoDB\Driver\BulkWrite(['upserted' => true]);
            $dateNow = date('Y-m-d H:i:s');
            $dateNow = $this->convertToMongoDateTime($dateNow);
            $data['created_date'] = $dateNow;
            $data['updated_date'] = $dateNow;
            if (!empty($this->currentUser)) {
                $data['created_by'] = $this->currentUser;
                $data['updated_by'] = $this->currentUser;
            }

            $data['created_at'] = $ipAddr;
            $data['updated_at'] = $ipAddr;

            $id = $bulk->insert($data);
            $table = $this->selectedDB.'.'.$this->collectionName;
            $result = $this->MongoClient->executeBulkWrite($table, $bulk);
            $dbRes = [
                            'status' => 'success',
                            '_id' => $id,
                    ];
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 500);
        }

        return $this->sendResult($dbRes);
    }

    public function DBUpdate($set_based_on, $new_value, $multi = true, $set = '$set', $customPushPull = false, $upsert = true)
    {
        if ($this->error !== false) {
            return $this->sendResult();
        }

        try {
            $bulk = new \MongoDB\Driver\BulkWrite(['upserted' => $upsert]);

            if (isset($new_value['_id'])) {
                unset($new_value['_id']);
            }

            $multi = $multi ? ['multi' => true] : [];
            $ipAddr = \Siler\Swoole\request()->server['remote_addr'];
            if (empty($customPushPull)) {
                $dateNow = date('Y-m-d H:i:s');
                $dateNow = $this->convertToMongoDateTime($dateNow);
                $new_value['released_date'] = $dateNow;
                $new_value['updated_date'] = $dateNow;

                if (!empty($this->currentUser)) {
                    $new_value['updated_by'] = $this->currentUser;
                }

                $new_value['updated_at'] = $ipAddr;

                $id = $bulk->update($set_based_on, [$set => $new_value], $multi);
            } else {
                $id = $bulk->update($set_based_on, $new_value, $multi);
            }
            $table = $this->selectedDB.'.'.$this->collectionName;

            $result = $this->MongoClient->executeBulkWrite($table, $bulk);
            $dbRes = [
                        'status' => 'success',
                        '_id' => $id,
                    ];
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 500);
        }

        return $this->sendResult($dbRes);
    }

    // public function DBdelete($_id)
    // {
    //     $setData = [
    //         'deleted' => 1,
    //         'deleted_date' => $this->convertToMongoDateTime(date('Y-m-d H:i:s')),
    //     ];

    //     if (!empty($this->currentUser)) {
    //         $setData['deleted_by'] = $this->currentUser;
    //     }

    //     $setData['deleted_at'] = getClientIpAddr();

    //     if (is_array($_id)) {
    //         foreach ($_id as $key => $value) {
    //             $_id[$key] = $this->convertToObjectId($value);
    //         }
    //         $returnResult = $this->DBUpdate(['_id' => ['$in' => $_id]], $setData);
    //     } else {
    //         $_id = $this->convertToObjectId($_id);
    //         $returnResult = $this->DBUpdate(['_id' => $_id], $setData);
    //     }

    //     return $returnResult;
    // }

    // public function DBdeleteInArray($condition, $targetArray)
    // {
    //     $setData = [
    //         $targetArray.'.$.deleted' => 1,
    //         $targetArray.'.$.deleted_date' => $this->convertToMongoDateTime(date('Y-m-d H:i:s')),
    //     ];

    //     if (!empty($this->currentUser)) {
    //         $setData[$targetArray.'.$.deleted_by'] = $this->currentUser;
    //     }
    //     $setData[$targetArray.'.$.deleted_at'] = getClientIpAddr();

    //     $returnResult = $this->DBUpdate($condition, $setData);

    //     return $returnResult;
    // }

    // public function DBUpdateInArray($condition, $targetArray, $newValues)
    // {
    //     $setData = [$targetArray.'.$.updated_date' => $this->convertToMongoDateTime(date('Y-m-d H:i:s'))];

    //     foreach ($newValues as $key => $val) {
    //         $setData[$targetArray.'.$.'.$key] = $val;
    //     }

    //     if (!empty($this->currentUser)) {
    //         $setData[$targetArray.'.$.updated_by'] = $this->currentUser;
    //     }
    //     $setData[$targetArray.'.$.updated_at'] = getClientIpAddr();

    //     // var_dump(json_encode($condition, JSON_PRETTY_PRINT), "\n\r\n\r", json_encode($setData, JSON_PRETTY_PRINT));
    //     $returnResult = $this->DBUpdate($condition, $setData);

    //     return $returnResult;
    // }

    // public function DBrestoreInArray($condition, $targetArray)
    // {
    //     $setData = [$targetArray.'.$.deleted' => 0,
    //                 $targetArray.'.$.restore_date' => $this->convertToMongoDateTime(date('Y-m-d H:i:s')), ];

    //     if (!empty($this->currentUser)) {
    //         $setData[$targetArray.'.$.restored_by'] = $this->currentUser;
    //     }
    //     $setData[$targetArray.'.$.restored_at'] = getClientIpAddr();

    //     $returnResult = $this->DBUpdate($condition, $setData);

    //     return $returnResult;
    // }

    // // function DBdelete( $set_based_on){
    // //     if($this->error !== false)
    // //         return $this->sendResult();

    // //     try
    // //     {
    // //         $id   = $set_based_on;
    // //         $bulk = fnew \MongoDB\Driver\BulkWrite;
    // //         $bulk->delete($id);

    // //         $result = $this->MongoClient->executeBulkWrite($this->table, $bulk);
    // //         $dbRes  = [ '_id'=> $id];

    // //     }
    // //     catch\Exception $e){
    // //         return $this->sendError($e->getMessage(), 500);

    // //     }

    // //     return $this->sendResult(($dbRes));
    // // }

    // /*!
    //  * This Function is commonly used for Finding, Searching, and returning GET section of an API services. It is like __SELECT__ command of your SQL Query.
    //  * \param Array $filter What you want to find out
    //  * \param Array $options What you want to limit, sort by, skip and etc. Please refer to MongoDB Documentation
    //  * \param Boolean $checkDeletedRow Set to __true__ if you want check the row of your result need to check deleted params. Set to __false__ if you don't want to check it. Default is __true__.
    //  */
    // public function DBfind($filter = [], $options = [], $checkDeletedRow = true)
    // {
    //     if ($this->error !== false) {
    //         return $this->sendResult();
    //     }
    //     try {
    //         if ($checkDeletedRow) {
    //             $filter['deleted'] = ['$exists' => false];
    //         }

    //         if (!isset($options['projection'])) {
    //             $options['projection'] = [];
    //             foreach ($this->getRequestColumns() as $column) {
    //                 $options['projection'][$column] = 1;
    //             }
    //         }

    //         $query = new \MongoDB\Driver\Query($filter, $options);
    //         $rows = $this->MongoClient->executeQuery($this->table, $query);
    //     } catch( \Exception $e) {
    //         return $this->sendError($e->getMessage(), 500);
    //     }

    //     // var_dump(($his->error), $this->table, $filter);
    //     return $this->sendResult($this->clear_result_format($rows));
    // }

    // public function dateTimeValue($param = false)
    // {
    //     if ($param == false) {
    //         $param = date('Y-m-d H:i:s');
    //     }
    //     $returnResult = $this->convertToMongoDateTime($param);

    //     return $returnResult;
    // }

    // public function DBfindGroup($match = [], $group = [], $sort = [], $checkDeletedRow = true)
    // {
    //     if ($this->error !== false) {
    //         return $this->sendResult();
    //     }

    //     try {
    //         if ($checkDeletedRow) {
    //             $match['deleted'] = ['$exists' => false];
    //         }

    //         $pipeline = [['$match' => $match], ['$group' => $group]];
    //         if (isset($sort) && !empty($sort)) {
    //             $pipeline[] = ['$sort' => $sort];
    //         }

    //         /*$aggregate = ['aggregate'=>$this->collection,
    //                     'pipeline'=>$getBody,
    //                     'cursor' => new stdClass,
    //                     ];*/
    //         $aggregate = ['aggregate' => $this->collection,
    //                       'pipeline' => $pipeline,
    //                       'cursor' => new stdClass(),
    //                     ];
    //         $command = new \MongoDB\Driver\Command($aggregate);

    //         $result = $this->MongoClient->executeCommand($this->db, $command);
    //     } catch( \Exception $e) {
    //         return $this->sendError($e->getMessage(), 500);
    //     }

    //     return $this->sendResult($this->clear_result_format($result));
    // }

    public function findBy(array $matchIndicator, $multi = false)
    {
        if (isset($matchIndicator['_id']) && is_string(($matchIndicator['_id']))) {
            $matchIndicator['_id'] = $this->convertToObjectId($matchIndicator['_id']);
        }
        $pipeline = [
            ['$match' => $matchIndicator],
        ];
        if ($multi == false) {
            $pipeline[] = ['$limit' => 1];
        }

        return $this->DBaggregate($pipeline);
    }

    public function DBaggregate($pipeline, $checkDeletedRow = false, $clearResultFormat = true)
    {
        // var_dump($pipeline);
        if ($this->error !== false) {
            return $this->sendResult();
        }

        try {
            if (!$checkDeletedRow) {
                $match = ['deleted' => ['$exists' => false]];
                $pipeline = array_merge(
                    [['$match' => $match]],
                    $pipeline
                );
            }

            $aggregate = ['aggregate' => $this->collectionName,
                          'pipeline' => $pipeline,
                          'cursor' => new  \stdClass(),
                        ];
            $command = new  \MongoDB\Driver\Command($aggregate);

            $MongoClient = getGlobalVar('MongoClient');
            // var_dump($this->collectionName);
            $result = $MongoClient->executeCommand($this->selectedDB, $command);
        } catch (\Throwable $e) {
            // var_dump('HELLO ERROR', $e);

            return $this->sendError($e->getMessage(), 500);
        }

        if ($clearResultFormat) {
            return $this->sendResult($this->clear_result_format($result));
        }

        return $result;
    }

    // private function getRequestColumns()
    // {
    //     if ($this->requestColumns && count($this->requestColumnsInArray) == 0) {
    //         $needClearFormat = 1;
    //         $requestColumns = explode(',', $this->requestColumns);

    //         foreach ($requestColumns as $key => $val) {
    //             $requestColumns[$key] = trim($val);
    //         }

    //         $this->requestColumnsInArray = $requestColumns;

    //         return $requestColumns;
    //     } else {
    //         return $this->requestColumnsInArray;
    //     }
    // }

    /*!
     * \param $mongoResult the Mongo Result, It will convert the Mongo result to Array and make a clear result as array also.<br/>
     * If the result is BSON\Object ID and BSON\UTCDateTime it will turn out into String. Note,   BSON\UTCDateTime will change into this format 'Y-m-d H:i:s'
     *
     * Example:<br/>
     * ```
     * $this->clear_result_format($mongoResult);
     * ```
     *
     * Return:
     * ```
     * array['_id' : 'as8ha0j7oajya79adfhlcva9', 'date'=>'2018-09-23 05:22:39']
     * ```
     *
     */
    public function clear_result_format($mongoResult)
    {
        $result = (!is_array($mongoResult)) ? $mongoResult->toArray() : $mongoResult;
        //var_dump($result);

        if (count($result) > 0) {
            $first_result = $result[0];
            $needClearFormat = 0;
            $requestColumns = [];

            if ($this->requestColumns) {
                $needClearFormat = 1;
                $requestColumns = explode(',', $this->requestColumns);

                foreach ($requestColumns as $key => $val) {
                    $requestColumns[$key] = trim($val);
                }
            }

            if ($needClearFormat == 0) {
                foreach ($first_result as $key => $value) {
                    //    var_dump($value);
                    if (is_object($value)) {
                        $needClearFormat = 1;
                    }
                }
            }

            if ($needClearFormat == 1) {
                foreach ($result as $k => $r) {
                    $result[$k] = $this->mongoObjectToPrimitive($r, $requestColumns);
                }
            }
        }

        return $result;
    }

    public function mongoObjectToPrimitive($r, $requestColumns)
    {
        foreach ($r as $key => $value) {
            if ($this->requestColumns && !empty($requestColumns)) {
                if (!in_array($key, $requestColumns)) {
                    unset($r->$key);
                    continue;
                }
            }
            if (is_object($value)) {
                $objectName = get_class($value);
                if ($objectName == 'MongoDB\BSON\ObjectId') {
                    $r->$key = $value->__toString();
                    continue;
                }
                if ($objectName == 'MongoDB\BSON\UTCDateTime') {
                    $tz = new  \DateTimeZone('Asia/Jakarta');
                    $dateVal = $value->toDateTime();
                    $dateVal->setTimeZone($tz);
                    $r->$key = $dateVal->format('Y-m-d H:i:s');
                    continue;
                }
                $cast = count((array) $value);
                if ($cast > 1) {
                    $r->$key = $this->mongoObjectToPrimitive($value, []);
                }
            }
            if (is_array($value)) {
                $r->$key = (array) $this->mongoObjectToPrimitive((object) $value, []);
            }
        }

        return $r;
    }

    // public function findAllMaster($request = [], $orderBy = [], $pageNo = 1, $limit = 50)
    // {
    //     if ($this->error !== false) {
    //         return $this->sendResult();
    //     }

    //     try {
    //         foreach ($request as $key => $value) {
    //             if (is_string($value)) {
    //                 $value = trim($value);
    //             }
    //             if (empty($value)) {
    //                 unset($request[$key]);
    //             } else {
    //                 $newRegex = $this->convertValueToMongoQuery($value);
    //                 if ($newRegex !== false) {
    //                     $request[$key] = $newRegex;
    //                 }
    //             }
    //         }

    //         $filter = [];

    //         if (count($request) != 0) {
    //             $filter = $request;
    //         }

    //         $sort = ['_id' => -1];
    //         $skip = $this->parsePageToSkip($pageNo, $limit);
    //         $opt = $skip;

    //         if (isset($orderBy) && is_array($orderBy) && !empty($orderBy)) {
    //             foreach ($orderBy as $key => $ord) {
    //                 $sort[$key] = $ord['asc'] ? 1 : -1;
    //             }
    //         }
    //         $opt['sort'] = $sort;

    //         return $this->DBFind($filter, $opt);
    //     } catch( \Exception $e) {
    //         return $this->sendError($e->getMessage(), 500);
    //     }
    // }

    // public function removeByID($_id)
    // {
    //     if ($this->error !== false) {
    //         return $this->sendResult();
    //     }
    //     try {
    //         $result = $this->DBdelete($_id);
    //     } catch( \Exception $e) {
    //         return $this->sendError($e->getMessage(), 500);
    //     }

    //     return $result;
    // }

    // public function updateByID($_id, $data)
    // {
    //     if ($this->error !== false) {
    //         return $this->sendResult();
    //     }
    //     try {
    //         $_id = $this->convertToObjectId($_id);
    //         $filter = ['_id' => $_id];

    //         return $this->DBUpdate($filter, $data);
    //     } catch( \Exception $e) {
    //         return $this->sendError($e->getMessage(), 500);
    //     }
    // }

    // public function insertModel($data)
    // {
    //     if ($this->error !== false) {
    //         return $this->sendResult();
    //     }

    //     try {
    //         $result = $this->DBinsert($data);
    //     } catch( \Exception $e) {
    //         return  $this->sendError($e->getMessage(), 500);
    //     }

    //     return $result;
    // }

    // public function parsePageToSkip($pageNo, $maxLimit = 50)
    // {
    //     $array = [
    //         'skip' => ($pageNo - 1) * $maxLimit,
    //         'limit' => (int) $maxLimit,
    //     ];

    //     return $array;
    // }

    public function sendError($errorMessage = '', $errorCode = false)
    {
        if ($errorCode) {
            $this->error_code = $errorCode;
        }
        if ($errorMessage) {
            $this->error = $errorMessage;
        }

        return $this->sendResult();
    }

    // public function convertToMongoObject($value)
    // {
    //     $type = $this->getValueType($value);

    //     if ($type == 'date') {
    //         $date = new  \DateTime($value['from'], new  \DateTimeZone('Asia/Jakarta'));
    //         $tz = new  \DateTimeZone('UTC');

    //         $date->setTimeZone($tz);
    //         $timestamp = $date->format('U');

    //         $from = new  \MongoDB\BSON\UTCDateTime($timestamp * 1000);

    //         $returnValue = [];
    //         if (isset($value['expr'])) {
    //             $returnValue[$value['expr']] = $from;
    //         } else {
    //             $returnValue = $from;
    //         }
    //     } elseif ($type == 'object_id') {
    //         $returnValue = $this->convertToObjectId($value['id']);
    //     } else {
    //         $returnValue = $value;
    //     }

    //     return $returnValue;
    // }

    // public function convertValueToMongoQuery($value)
    // {
    //     if (is_string($value)) {
    //         $searchQuery = new \MongoDB\BSON\Regex($value, 'i');
    //         $return = ['$regex' => $searchQuery];
    //     } elseif (is_numeric($value)) {
    //         $return = $value;
    //     } elseif (is_array($value)) {
    //         /* SET VALUE FOR NUMBER */
    //         if ($this->getValueType($value) == 'number') {
    //             /* Set Value for Number['range'] */
    //             if ($value['option'] == 'range') {
    //                 if (!isset($value['from'])) {
    //                     $return = false;
    //                 } else {
    //                     $rangeArray = ['$gte' => (int) $value['from']];

    //                     if (isset($value['to'])) {
    //                         $rangeArray['$lte'] = (int) $value['to'];
    //                     }

    //                     $return = $rangeArray;
    //                 }
    //             } elseif ($value['option'] == 'greater_than') {
    //                 /* Set Value for Number['greater than'] */
    //                 if (!isset($value['value'])) {
    //                     return false;
    //                 }
    //                 $return = ['$gt' => intval($value['value'])];
    //             } elseif ($value['option'] == 'less_than') {
    //                 /* Set Value for Number['greater than'] */
    //                 if (!isset($value['value'])) {
    //                     return false;
    //                 }
    //                 $return = ['$lt' => intval($value['value'])];
    //             } elseif ($value['option'] == 'greater_than_or_equal') {
    //                 /* Set Value for Number['greater than'] */
    //                 if (!isset($value['value'])) {
    //                     return false;
    //                 }
    //                 $return = ['$gte' => intval($value['value'])];
    //             } elseif ($value['option'] == 'less_than_or_equal') {
    //                 /* Set Value for Number['greater than'] */
    //                 if (!isset($value['value'])) {
    //                     return false;
    //                 }
    //                 $return = ['$lte' => intval($value['value'])];
    //             } else {
    //                 /* Set Value for Number['equal'] */
    //                 if (!isset($value['value'])) {
    //                     return false;
    //                 }
    //                 $return = intval($value['value']);
    //             }
    //         } elseif ($this->getValueType($value) == 'date') {
    //             if (!isset($value['from'])) {
    //                 $return = false;
    //             } else {
    //                 $date = new  \DateTime($value['from'].' 00:00:00', new  \DateTimeZone('Asia/Jakarta'));
    //                 $tz = new  \DateTimeZone('UTC');

    //                 $date->setTimeZone($tz);
    //                 $timestamp = $date->format('U');

    //                 $from = new  \MongoDB\BSON\UTCDateTime($timestamp * 1000);
    //                 $rangeArray = ['$gte' => $from];

    //                 if (isset($value['to']) && trim($value['to']) != '') {
    //                     $timeDateTo = new  \DateTime($value['to'].' 23:59:59', new  \DateTimeZone('Asia/Jakarta'));
    //                     $timeTo = $timeDateTo->format('U');
    //                     // var_dump($timeDateTo->setTimeZone(new  \DateTimeZone('Asia/Jakarta')));
    //                     $to = new  \MongoDB\BSON\UTCDateTime($timeTo * 1000);
    //                     $rangeArray['$lte'] = $to;
    //                 } elseif (isset($value['from']) && (!isset($value['to']) || trim($value['to']) == '')) {
    //                     $timeDateTo = new  \DateTime($value['from'].' 23:59:59', new  \DateTimeZone('Asia/Jakarta'));
    //                     $timeTo1Day = $timeDateTo->format('U');
    //                     $to = new  \MongoDB\BSON\UTCDateTime($timeTo1Day * 1000);

    //                     $rangeArray['$lt'] = $to;
    //                 }
    //                 $return = $rangeArray;
    //             }
    //         } elseif ($this->getValueType($value) == 'object_id') {
    //             return $this->convertToObjectId($value['id']);
    //         } elseif ($this->getValueType($value) == 'exists') {
    //             if (!empty($value['exists'])) {
    //                 $return = ['$exists' => true];
    //             } else {
    //                 $return = ['$exists' => false];
    //             }
    //         } elseif ($this->getValueType($value) == 'multi_value') {
    //             $return = ['$in' => $value['in']];
    //         } elseif ($this->getValueType($value) == 'equal') {
    //             $return = $value['eq'];
    //         }
    //     } else {
    //         $return = false;
    //     }

    //     return $return;
    // }

    // public function getValueType($value)
    // {
    //     if (isset($value['option'])) {
    //         return 'number';
    //     } elseif (isset($value['from'])) {
    //         return 'date';
    //     } elseif (isset($value['exists'])) {
    //         return 'exists';
    //     } elseif (isset($value['id'])) {
    //         return 'object_id';
    //     } elseif (isset($value['in'])) {
    //         return 'multi_value';
    //     } elseif (isset($value['eq'])) {
    //         return 'equal';
    //     }
    // }

    // public function aggregateDB($pipeline)
    // {
    //     global $selectedDB;

    //     $command = new  \MongoDB\Driver\Command([
    //         'aggregate' => $this->collectionName,
    //         'pipeline' => $pipeline,
    //         'cursor' => new  \stdClass(),
    //     ]);
    //     $cursor = $this->MongoClient->executeCommand($selectedDB, $command);

    //     return $cursor->toArray();
    // }

    // public function columnsMapping(array $requestedColumns)
    // {
    //     $respondsColumns = [];

    //     if ($requestedColumns && count($requestedColumns) >= 1) {
    //         foreach ($requestedColumns as $column) {
    //             $column = trim($column);
    //             if (isset($this->requestMapping[$column])) {
    //                 $respondsColumns[$column] = $this->requestMapping[$column];
    //             }
    //         }
    //     }

    //     return $respondsColumns;
    // }

    public function convertToObjectId($_id, $createNew = false)
    {
        try {
            !$createNew ?
                $val = ((is_string($_id)) && (strlen($_id) == 24) && preg_match('/^[a-fA-F0-9]{24}/', $_id)) ? new  \MongoDB\BSON\ObjectId("$_id") : $_id :
                $val = new  \MongoDB\BSON\ObjectId();

            return $val;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function convertToMongoDateTime($datetime)
    {
        $date = new  \DateTime($datetime, new  \DateTimeZone('Asia/Jakarta'));
        $tz = new  \DateTimeZone('UTC');

        $date->setTimeZone($tz);
        $timestamp = $date->format('U');

        return new  \MongoDB\BSON\UTCDateTime($timestamp * 1000);
    }

    // public function setCurrentUser($userId)
    // {
    //     $this->currentUser = $this->convertToObjectId($userId);
    // }

    // public function createRegex($search, $options = 'i')
    // {
    //     return new \MongoDB\BSON\Regex($search, $options);
    // }
}
