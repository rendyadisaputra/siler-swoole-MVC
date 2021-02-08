<?php

use App\Models\MongoModel;
use App\Services\MediaUploadManager;

$postMedia = function ($C) {
    
    /** Media should support with sending a file  
     * it is under $C->clientRequest->files
    */
    
    if(empty( $C->clientRequest->files)){
        return $C->createResponse(['error' => 'empty file'], 403);
    } else{
        if(!isset($C->clientRequest->files['file']) || empty($C->clientRequest->files['file']['name'])){
            return $C->createResponse(['error' => 'sent "file" not found'], 403);
        }
    }

    $uploadFile = MediaUploadManager::process($C->clientRequest->files, FILE_UPLOAD_DIR, true);
    var_dump($uploadFile);
    return false;
    

    $MediasModel = MongoModel::MediasModel();

    /** Checking is Medias available */
    $check = $MediasModel->findByUniqueTitle($data['title']);
    if (!empty($check)) {
        return $C->createResponse(['error' => 'the Media already exist'], 403);
    }

    $data = array_merge($defaultData, $data);
    $membersData = $MediasModel->createMedias($data);

    try {
        $C->sendResponse($membersData, 200);
    } catch (Error $e) {
        var_dump('EERRROR HERE');
    }
};
