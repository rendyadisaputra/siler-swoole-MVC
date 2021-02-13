<?php
namespace App\Functions\Model\RulesModel;
use App\Functions\Model\MongoFunc;

function isAuthorizedRule($route, $userRolesName) {

    $pipeline = [
        [
            '$match' => [
                'route' => $route
            ]
        ],
        [
            '$project'=> [
                'idc' => '$_id'
            ]
        ],
        [
            '$lookup'=> [
                'from'=> 'Auth_rules_group',
                'localField'=> 'idc',
                'foreignField'=> 'rules',
                'as'=> 'rules_g'
            ],
           
        ],
        [
            '$unwind'=> '$rules_g'
        ],
        [
            '$lookup'=> [
                'from'=> 'Auth_roles',
                'localField'=> "rules_g._id",
                'foreignField'=> 'rules_group',
                'as'=> 'roles'
            ]
        ],
        [
            '$project'=> ['roles.name' => 1]
        ],
        [
            '$unwind'=> '$roles'
        ],
        [
            '$match'=> [
                'roles.name'=> $userRolesName
            ]
        ]
    ];
    
    $roles = MongoFunc\DBAggregate($pipeline, collectionName, DB_NAME);

    return $roles;
};
