<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require 'config.php';

function generate_jwt($user_id) {
    global $secret_key, $issuer, $audience;

    $issuedAt = time();
    $expire = $issuedAt + 3600; // Token có hiệu lực 1 giờ

    $payload = [
        'iss' => $issuer,
        'aud' => $audience,
        'iat' => $issuedAt,
        'exp' => $expire,
        'data' => [
            'user_id' => $user_id
        ]
    ];

    return JWT::encode($payload, $secret_key, 'HS256');
}

// Ví dụ khi login thành công
$user_id = 123;
$token = generate_jwt($user_id);
echo json_encode(["token" => $token]);
