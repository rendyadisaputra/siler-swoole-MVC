<?php

$updateEmailTemplates = function ($mongoModel, $args = null) {
    $id = $args[0];

    $newData = $args[1];
    $id = $mongoModel->convertToObjectId($id);

    $EmailTemplatesData = $mongoModel->DBupdate($basedOn = ['_id' => $id], $new = $newData);

    return ['success' => true];
};
