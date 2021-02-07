<?php

$findRulesGroup = function ($mongoModel) {
    $pipeline = [
        [
            '$match' => [
                'rules' => [
                    '$exists' => true,
                ],
            ],
        ],
        [
            '$lookup' => [
                'from' => 'Auth_rules',
                'let' => ['list_of_rules' => '$rules'],
                'pipeline' => [
                    [
                            '$match' => [
                                '$expr' => [
                                    '$and' => [
                                            ['$in' => ['$_id', '$$list_of_rules']],
                                    ],
                                ],
                            ],
                    ],
                    [
                        '$project' => [
                            'description' => 1,
                            'name' => 1,
                            'route' => 1,
                        ],
                    ],
                    ],
                'as' => 'rules',
            ],
        ],
    ];

    $rulesGroup = $mongoModel->DBAggregate($pipeline);
    // var_dump($getMembersData);

    return $rulesGroup;
};
