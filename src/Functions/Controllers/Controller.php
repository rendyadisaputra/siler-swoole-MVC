<?php
namespace App\Functions\Controllers\Controller;

function successResponse($value, int $successCode = 200){
    return [
        'result' => $value,
        'code' => $successCode
    ];
}

function errorResponse($errorMessage, int $errorCode = 500){
    return [
        'errorMessage' => $errorMessage,
        'code' => $errorCode,
        'error' => 1
    ];
}

function checkArraySpecs(array $arrayNeeded, array $Arrays)
    {
        foreach ($arrayNeeded as $val) {
            if (!isset($Arrays[$val])) {
                return false;
            } elseif (empty($Arrays[$val])) {
                return false;
            }
        }

        return true;
    }