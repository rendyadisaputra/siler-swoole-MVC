<?php

namespace App\Functions;


function getGlobalVar($var) {
    global $globalArray;
    return isset($globalArray[$var])? $globalArray[$var]:null;
}

function setGlobalVar($var, $value){
    global $globalArray;
    return $globalArray[$var] = $value;
}
