<?php
function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64UrlDecode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}

function createJWT($payload, $secret, $algorithm = 'HS256') {
    $header = json_encode(['typ' => 'JWT', 'alg' => $algorithm]);
    $base64UrlHeader = base64UrlEncode($header);
    $base64UrlPayload = base64UrlEncode(json_encode($payload));
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
    $base64UrlSignature = base64UrlEncode($signature);
    return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
}

function decodeJWT($jwt, $secret, $algorithm = 'HS256') {
    list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $jwt);
    $header = json_decode(base64UrlDecode($base64UrlHeader), true);
    $payload = json_decode(base64UrlDecode($base64UrlPayload), true);
    $signature = base64UrlDecode($base64UrlSignature);

    $validSignature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

    if ($signature !== $validSignature) {
        throw new Exception('Invalid token signature');
    }

    return $payload;
}
