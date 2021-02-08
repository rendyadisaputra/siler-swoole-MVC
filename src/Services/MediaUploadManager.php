<?php

namespace App\Services;

class MediaUploadManager
{
    public static function process(array $filesUploadRequest, string $moveTargetDir, $useSecondOptionName = false)
    {
        $moveTargetDir = realpath($moveTargetDir).'/';
        $tmpFileName = $filesUploadRequest['file']['tmp_name'];
        $realName = $filesUploadRequest['file']['name'];
        $pathInfo = pathinfo($realName);

        $tmpUniqName = explode('.', $tmpFileName, 2);
        if ($useSecondOptionName == false) {
            $newFileName = $tmpUniqName[1].'.'.$pathInfo['extension'];
        } else {
            $fileBase = preg_replace('/[^A-Za-z0-9]/', '-', $pathInfo['filename']);
            $newFileName = strtolower($tmpUniqName[1].'.'.$fileBase.'.'.$pathInfo['extension']);
        }

        $movedFilePath = $moveTargetDir.$newFileName;
        $upload_file = move_uploaded_file($tmpFileName, $movedFilePath);

        return [
            'upload_file' => $upload_file,
            'real_path' => $moveTargetDir.$newFileName,
            'file_name' => $newFileName,
            'extension' => $pathInfo['extension'],
            'real_name' => $pathInfo['basename'],
        ];
    }
}
