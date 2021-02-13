<?php
namespace App\Functions\Model\RolesModel;
use App\Functions\Model\MongoFunc;


function findRoles($filterQuery, $unwind = false) {
    if (!empty($filterQuery)) {
        if (isset($filterQuery['_id']) && is_string($filterQuery['_id'])) {
            $filterQuery['_id'] = MongoFunc\convertToObjectId($filterQuery['_id']);
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
    if($unwind == true){
        array_push($pipeline, 
        [
            '$unwind'=> '$rules_group'
        ],
        [
            '$unwind'=> '$rules_group.rules'
        ],
        [
            '$project' => [
                'rules_group'=> [
                    
                    'name' => '$rules_group.name',
                ],
                'rules_name'=>'$rules_group.rules.name',
                'rules_routes' => '$rules_group.rules.route'
            ]
        ]);
    }
    
    $roles = MongoFunc\DBAggregate($pipeline, collectionName, DB_NAME);

    return $roles;
};
