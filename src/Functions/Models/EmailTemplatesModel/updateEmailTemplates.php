<?php
namespace App\Functions\Model\EmailTemplatesModel;
use App\Functions\Model\MongoFunc;

function updateEmailTemplates(string $id, array $newData) {
    $id = MongoFunc\convertToObjectId($id);
    $EmailTemplatesData = MongoFunc\DBupdate($basedOn = ['_id' => $id], $new = $newData, collectionName, DB_NAME);

    return ['success' => true];
};