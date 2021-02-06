<?php

use App\Services\JwtToken;
use Siler\Swoole;
$checkValidation = function ($resp) {
    $data = ($raw = Swoole\raw()) ? json_decode($raw,1) : null;
    
    if(!isset($data['token'])){
        $resp->sendResponse(['error' => 'need Token', 'checkToken'=>true], 400);
    }
    $dec = null;
    try{
        $dec = JwtToken::decode($data['token']);
    } catch(Exception $e){
    
        $resp->sendResponse(['error' => $e->getMessage(), 'checkToken'=>false], 401);
    }
    
    if($dec!=null){
        $resp->sendResponse(['success' => true, 'checkToken'=>true, "dec"=>$dec], 200);
    }
    
};
