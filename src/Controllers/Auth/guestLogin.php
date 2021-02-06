<?php

use App\Services\JwtToken;

$guestLogin = function ($resp) {
    $generatedToken = JwtToken::encode($data = [
        // 'userId' => $rs['id'], // userid from the users table
        'userName' => 'guest-'.time().rand(), // User name
    ]);

    $resp->sendResponse(['success' => true, 'token' => $generatedToken], 200);
};
