<?php

$findMembers = function ($mongoModel) {
    $getMembersData = $mongoModel->DBAggregate([]);
    // var_dump($getMembersData);

    return $getMembersData;
};
