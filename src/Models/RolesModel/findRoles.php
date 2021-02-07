<?php
$findRoles = function ($mongoModel) {
   
    $roles = $mongoModel->DBAggregate([]);
    // var_dump($getMembersData);

    return $roles;
};
