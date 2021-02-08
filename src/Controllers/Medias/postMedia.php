<?php

use App\Models\MongoModel;
use App\Services\MediaUploadManager;

$postMedia = function ($C) {
    /** Media should support with sending a file
     * it is under $C->clientRequest->files.
     */
    if (empty($C->clientRequest->files)) {
        return $C->createResponse(['error' => 'empty file'], 403);
    } else {
        if (!isset($C->clientRequest->files['file']) || empty($C->clientRequest->files['file']['name'])) {
            return $C->createResponse(['error' => 'sent "file" not found'], 403);
        }
    }

    $uploadFile = MediaUploadManager::process($C->clientRequest->files, FILE_UPLOAD_DIR, true);
    $publicContentURL = URL_HOST.'images/'.$uploadFile['file_name'];
    $response = [
        'success' => true,
        'url_path' => $publicContentURL,
    ];

    $data = array_merge($uploadFile, ['url_path' => $publicContentURL], $C->clientRequest->files['file']);

    $MediasModel = MongoModel::MediasModel();
    $check = $MediasModel->findBy(['url_path' => $data['url_path']]);
    // var_dump($check);

    if (!empty($check['result'])) {
        return $C->createResponse(['error' => 'the Media already exist'], 403);
    }

    //insert to database
    $MediasModel->createMedias($data);

    return $C->createResponse($response, 200);
};
