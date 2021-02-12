<?php
namespace App\Functions\Model\EmailTemplatesModel;
use App\Functions\Model\MongoFunc;

function createEmailTemplates($data) {
    $EmailTemplatesData = MongoFunc\DBInsert($data, collectionName, DB_NAME);

    return ['success' => true, $EmailTemplatesData];
};
