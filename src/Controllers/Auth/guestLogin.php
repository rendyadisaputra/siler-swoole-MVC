<?php

use App\Services\JwtToken;
use App\Functions;
use App\Functions\Model\RolesModel;

$guestLogin = function ($resp) {
    $generatedToken = JwtToken::encode($data = [
        'userName' => 'guest-'.time().rand(), // User name,
        'roles' => 'guest'
    ]);
    
    //get Saved Roles in the global variable cache
    $guestRolesCache = Functions\getGlobalVar('roles/guest');

    if(is_null($guestRolesCache)){
        $guestRoles = RolesModel\findRoles(['name'=>'guest'], $unwind = true);
        $guestRolesCache = ['data'=> $guestRoles, 'expr' => time() + 60];
        Functions\setGlobalVar('roles/guest', $guestRolesCache);
    } else {
        if(time() > $guestRolesCache['expr']){
            $guestRoles = RolesModel\findRoles(['name'=>'guest'], $unwind = true);
            $guestRolesCache = ['data'=> $guestRoles, 'expr' => time() + 60];
            Functions\setGlobalVar('roles/guest', $guestRolesCache);
        }
    }

    $resp->sendResponse(['success' => true, 'token' => $generatedToken], 200);
};
