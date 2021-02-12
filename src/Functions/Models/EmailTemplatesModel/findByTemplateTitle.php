<?php
namespace App\Functions\Model\EmailTemplatesModel;
use App\Functions\Model\MongoFunc;

function findByTemplateTitle($title) {
    $pipeline = [
        ['$match'=> [
                'title' => $title
            ]
        ]
    ];
    $getData = MongoFunc\DBAggregate($pipeline, collectionName, DB_NAME);
    return $getData['result'];
};
