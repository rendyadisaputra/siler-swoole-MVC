<?php
use App\Models\MongoModel;

$createTemplate = function ($C) {
    if (is_null($data = json_decode($C->postBody, 1))) {
        return $C->createResponse(['error' => 'unsupported format'], 402);
    }

    /* Checking minimum needed specs */
    if (!$C->checkArraySpecs(['content', 'title'], $data)) {
        return $C->createResponse(['error' => 'invalid minimum json requirement'], 402);
    }

    /** setup the default data */
    $defaultData = [
        'title' => 'your title is here ', // unique
        'content' => 'your content is here',
        'hashtags' => ['test', 'trial'],
        'category' => 'no-category',
        'slug' => 'no-slug', // unique
    ];

    /* title should be unique in lower case */
    $data['title'] = strtoupper(trim($data['title']));

    $EmailTemplatesModel = MongoModel::EmailTemplatesModel();

    /** Checking is EmailTemplates available */
    $check = $EmailTemplatesModel->findByTemplateTitle($data['title']);
    if (!empty($check)) {
        return $C->createResponse(['error' => 'the article already exist'], 403);
    }

    $data = array_merge($defaultData, $data);
    $membersData = $EmailTemplatesModel->createEmailTemplates($data);

    try {
        $C->sendResponse($membersData, 200);
    } catch (Error $e) {
        var_dump('EERRROR HERE');
    }
};