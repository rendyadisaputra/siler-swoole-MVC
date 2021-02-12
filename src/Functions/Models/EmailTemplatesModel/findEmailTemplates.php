<?php
namespace App\Functions\Model\EmailTemplatesModel;
use App\Functions\Model\MongoFunc;

function findEmailTemplates($filterQuery = []) {
    if (!empty($filterQuery)) {
        if (isset($filterQuery['_id']) && is_string($filterQuery['_id'])) {
            $filterQuery['_id'] = MongoFunc\convertToObjectId($filterQuery['_id']);
        }
    }

    $pipeline = [];

    if ($filterQuery) {
        $pipeline[0] = [
            '$match' => [],
        ];
        $pipeline[0]['$match'] = array_merge($pipeline[0]['$match'], $filterQuery);
    } 
    $EmailTemplates = MongoFunc\DBAggregate($pipeline, collectionName, DB_NAME);

    return $EmailTemplates;
};