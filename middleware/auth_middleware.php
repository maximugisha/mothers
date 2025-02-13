<?php
require_once '../config/jwt_config.php';
require_once '../utils/jwt_utils.php';

function authenticate() {
    $headers = apache_request_headers();

    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(array("message" => "Access denied."));
        exit();
    }

    $jwt = str_replace('Bearer ', '', $headers['Authorization']);

    try {
        $decoded = decodeJWT($jwt, JWT_SECRET_KEY, JWT_ALGORITHM);
        return $decoded->data;
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
        exit();
    }
}

function authorize($requiredRole) {
    $user = authenticate();
    if ($user->role !== $requiredRole && $user->role !== 'admin') {
        http_response_code(403);
        echo json_encode(array("message" => "You don't have permission to access this resource."));
        exit();
    }
    return $user;
}