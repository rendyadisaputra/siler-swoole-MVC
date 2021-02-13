<?php
namespace App\Functions\Controllers\Auth;

use App\Functions\Controllers\Controller;
use App\Services\JwtToken;
use App\Functions;
use App\Functions\Model\RulesModel;
use App\Functions\Services\InternalCacher;

function isAuthorizedRoute($route, $userRoles){
    $isAuthorized = InternalCacher\get($route."|".$userRoles, function() use ($route, $userRoles){
         return RulesModel\isAuthorizedRule($route, $userRoles);
     }, time()+60);
    
    if(!empty($isAuthorized['result'])){
        return true;
    }
    
    return false;
}

