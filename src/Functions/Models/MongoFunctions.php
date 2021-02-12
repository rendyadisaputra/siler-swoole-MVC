<?php
namespace App\Functions\Model\MongoFunc;
use function App\Functions\getGlobalVar;
use function App\Functions\setGlobalVar;

const APP_VAL = "hello";

function MongoClientModel(){
    $MongoClient = getGlobalVar('MongoClient');
    if(is_null($MongoClient)){
        $MongoClient = new \MongoDB\Driver\Manager(MONGODB_HOST);
        setGlobalVar('MongoClient', $MongoClient);
    }
    return $MongoClient;
}

function convertToMongoDateTime($datetime){
    $date = new  \DateTime($datetime, new  \DateTimeZone('Asia/Jakarta'));
    $tz = new  \DateTimeZone('UTC');

    $date->setTimeZone($tz);
    $timestamp = $date->format('U');

    return new \MongoDB\BSON\UTCDateTime($timestamp * 1000);
}

function DBinsert($data, $collectionName, $db){
    $result = null;
    $MongoClient = MongoClientModel();
    
    $ipAddr = \Siler\Swoole\request()->server['remote_addr'];

    try {
        $bulk = new \MongoDB\Driver\BulkWrite(['upserted' => true]);
        $dateNow = date('Y-m-d H:i:s');
        $dateNow = convertToMongoDateTime($dateNow);
        $data['created_date'] = $dateNow;
        $data['updated_date'] = $dateNow;

        if(isset($data['current_user'])){
            $data['created_by'] = $data['current_user'];
            $data['updated_by'] = $data['current_user'];
        }

        $data['created_at'] = $ipAddr;
        $data['updated_at'] = $ipAddr;

        $id = $bulk->insert($data);
        $table = $db.'.'.$collectionName;
        $result = $MongoClient->executeBulkWrite($table, $bulk);
        $dbRes = [ 'status' => 'success',
                   '_id' => $id];

    } catch (\Exception $e) {
        return ['error' => 1, 'message' => $e->getMessage()];
    }

    return sendResult($dbRes);
}

function DBAggregate($pipeline, $collectionName, $db, $checkDeletedRow = false, $clearResultFormat = true){
    $result = null;
    $MongoClient = MongoClientModel();
    
    try {
        if (!$checkDeletedRow) {
            $match = ['deleted' => ['$exists' => false]];
            $pipeline = array_merge(
                [['$match' => $match]],
                $pipeline
            );
        }

        $aggregate = ['aggregate' => $collectionName,
                      'pipeline' => $pipeline,
                      'cursor' => new  \stdClass(),
                    ];
        $command = new  \MongoDB\Driver\Command($aggregate);

        // var_dump($this->collectionName);
        $result = $MongoClient->executeCommand($db, $command);
    } catch (\Throwable $e) {
        // var_dump('HELLO ERROR', $e);

        return ['error' => 1, 'message' => $e->getMessage()];
    }

    if ($clearResultFormat) {

        return sendResult(clear_result_format($result));
    }

    return $result->toArray();
}

function clear_result_format($mongoResult){
    $result = (!is_array($mongoResult)) ? $mongoResult->toArray() : $mongoResult;
    //var_dump($result);

    if (count($result) > 0) {
        $first_result = $result[0];
        $needClearFormat = 0;
        $requestColumns = [];

        // if ($this->requestColumns) {
        //     $needClearFormat = 1;
        //     $requestColumns = explode(',', $this->requestColumns);

        //     foreach ($requestColumns as $key => $val) {
        //         $requestColumns[$key] = trim($val);
        //     }
        // }

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
                $result[$k] = mongoObjectToPrimitive($r, $requestColumns);
            }
        }
    }

    return $result;
}

function sendResult($result = ''){
    
    return [
            'result' => $result,
            'error' => false,
        ];
    
}

function mongoObjectToPrimitive($r, $requestColumns){
    foreach ($r as $key => $value) {
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
                $r->$key = mongoObjectToPrimitive($value, []);
            }
        }
        if (is_array($value)) {
            $r->$key = (array) mongoObjectToPrimitive((object) $value, []);
        }
    }

    return $r;
}

function convertToObjectId($_id, $createNew = false){
    try {
        !$createNew ?
            $val = ((is_string($_id)) && (strlen($_id) == 24) && preg_match('/^[a-fA-F0-9]{24}/', $_id)) ? new  \MongoDB\BSON\ObjectId("$_id") : $_id :
            $val = new  \MongoDB\BSON\ObjectId();

        return $val;
    } catch (\Exception $e) {
        return false;
    }
}