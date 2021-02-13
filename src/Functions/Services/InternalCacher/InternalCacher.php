<?php
namespace App\Functions\Services\InternalCacher;
use App\Functions;

$internalCaches = [];
function get($variable, $callbackIfNotExists, int $timeExpires){
    global $internalCaches;
    if(!isset($internalCaches[$variable])){
            return setFunc($internalCaches[$variable], $callbackIfNotExists, $timeExpires);
        }
    else{
        if(time() > $internalCaches[$variable]['exp'] ){
            return setFunc($internalCaches[$variable], $callbackIfNotExists, $timeExpires);
        }

        return $internalCaches[$variable]['value'];
    }
}

function setFunc(&$internalCache, $callbackIfNotExists, $timeExpires){
    $data = ['value' => $callbackIfNotExists(), 'exp' => $timeExpires];
    $internalCache = $data;
    return $data['value'];
}