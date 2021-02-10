<?php

$findEmailTemplates = function ($mongoModel, $args) {
    $filterQuery = null;
    if (!empty($args)) {
        $filterQuery = $args[0];
        if (isset($filterQuery['_id']) && is_string($filterQuery['_id'])) {
            $filterQuery['_id'] = $mongoModel->convertToObjectId($filterQuery['_id']);
        }
    }

    $pipeline = [
        [
            '$match' => [
                'rules_group' => [
                    '$exists' => true,
                ],
            ],
        ],
        [
            '$lookup' => [
                'from' => 'Auth_rules_group',
                'let' => [
                    'list_group' => '$rules_group',
                ],
                'pipeline' => [
                    [
                        '$match' => [
                            '$expr' => [
                                '$and' => [
                                    [
                                        '$in' => [
                                            '$_id',
                                            '$$list_group',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        '$project' => [
                            'description' => 1,
                            'name' => 1,
                            'route' => 1,
                            'rules' => 1,
                        ],
                    ],
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
                ],
                'as' => 'rules_group',
            ],
        ],
    ];

    if ($filterQuery) {
        $pipeline[0]['$match'] = array_merge($pipeline[0]['$match'], $filterQuery);
    }
    // var_dump($filterQuery);
    // var_dump(json_encode($pipeline, JSON_PRETTY_PRINT));
    $EmailTemplates = $mongoModel->DBAggregate($pipeline);

    // var_dump($getMembersData);

    return $EmailTemplates;
};
