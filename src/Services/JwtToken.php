<?php

namespace App\Services;

use Firebase\JWT;

class JwtToken
{
    public static function encode($data)
    {
        $tokenId = base64_encode(random_bytes(32));
        $issuedAt = time();
        $notBefore = $issuedAt + 3;             //Adding 3 seconds
        $expire = $notBefore + 3600*24;            // Adding 60 seconds
        $serverName = 'swoole-api'; // Retrieve the server name from config file

        /*
         * Create the token as an array
         */
        $data = [
        'iat' => $issuedAt,         // Issued at: time when the token was generated
        'jti' => $tokenId,          // Json Token Id: an unique identifier for the token
        'iss' => $serverName,       // Issuer
        'nbf' => $notBefore,        // Not before
        'exp' => $expire,           // Expire
        'data' => $data,
    ];

        $secretKey = base64_decode(JWTKEY);

        return JWT\JWT::encode(
            $data,      //Data to be encoded in the JWT
            $secretKey, // The signing key
            'HS256'
        );
    }

    public static function decode($encodedString)
    {
        $secretKey = base64_decode(JWTKEY);
        return JWT\JWT::decode($encodedString, $secretKey, ['HS256']);
    }
}
