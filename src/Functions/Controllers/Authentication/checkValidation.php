<?php
namespace App\Functions\Controllers\Auth;
use App\Functions\Controllers\Controller;
use App\Services\JwtToken;
use App\Functions;
use App\Functions\Model\RolesModel;


function checkValidation(&$req, $onSuccesCallback){
    
   
    if(!isset($req->header['authorization'])){
        $req->errorResponse = Controller\errorResponse("Unauthorized access", 401);
        return  $req->errorResponse;
    }
    
    $decData = null;
    try{
        $decData = JwtToken::decode($req->header['authorization']);
        $req->userData = $decData->data;
        $route = "/".strtolower($req->server['request_method']).$req->server['path_info'];
        $route = (substr($route, -1) != "/") ? $route.="/" : $route;

        $isAuthorized = isAuthorizedRoute($route, $req->userData->roles);

        if($isAuthorized == false){
            $req->errorResponse = Controller\errorResponse("Unauthorized access. fail routes", 401);
            return $req->errorResponse;
        }

    } finally {
        
        if($decData==null){
            $req->errorResponse = Controller\errorResponse("Unauthorized access. Invalid or expired token", 401);
            return $req->errorResponse;
        }
    }
    
    if($req->multiHitProtection == true){
        createKey($req);

        if(multiHitChecking($req)){
            $req->errorResponse = Controller\errorResponse("Unauthorized access. Waiting prev process (Multi Hit protection activated)", 401);
            return $req->errorResponse;
        }

        return (function() use ($req, $onSuccesCallback){
            $return = $onSuccesCallback();
            // var_dump("Return ", $req);
            clearMultiHitCache($req);
            return $return;
        })();
    }

    return $onSuccesCallback();
}

function multiHitChecking($req){

    $c = $req->table->get($req->hitKey);
    // var_dump($c);
    if($c !== false){
        return true;
    }

    $req->table->set($req->hitKey, array('value'=> "1"));
    return false;
}

function clearMultiHitCache($req){
    $req->table->del($req->hitKey);
}

function createKey(&$req){
    $req->hitKey = "mh"."/".strtolower($req->server['request_method']).$req->server['path_info'].($req->userData->userName);
    return $req->hitKey;
}

