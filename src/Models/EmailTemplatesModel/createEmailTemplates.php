<?php

$createEmailTemplates = function ($mongoModel, $args = null) {
    $data = $args[0];
    $EmailTemplatesData = $mongoModel->DBInsert($data);

    return ['success' => true, $EmailTemplatesData];
};
