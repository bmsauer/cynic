<?php

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class CynicJWT {
    public static function generate_jwt($username, $role){
        $secretKey = getenv('SECRET_KEY');
        $issuedAt = new \DateTimeImmutable();
        $expire = $issuedAt->modify('+1 hour')->getTimestamp();
        $serverName = "cynic";

        $data = [
            'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
            'iss'  => $serverName,                       // Issuer
            'nbf'  => $issuedAt->getTimestamp(),         // Not before
            'exp'  => $expire,                           // Expires in 1 hour
            'username' => $username,                     // User name
            'role' => $role,
        ];
        return JWT::encode($data, $secretKey, 'HS512');
    }

    public static function decode_jwt($jwt){
        $secretKey = getenv('SECRET_KEY');
        //TODO: better exception handling here, see https://github.com/firebase/php-jwt/blob/018dfc4e1da92ad8a1b90adc4893f476a3b41cb8/src/JWT.php#L67 for exceptions thrown
        $token = JWT::decode($jwt, new Key($secretKey, 'HS512'));
        return $token;
    }
}