<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/User.php';
include_once '../config/jwt_config.php';
include_once '../utils/jwt_utils.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password)) {
    $user->email = $data->email;

    if ($user->emailExists()) {
        if (password_verify($data->password, $user->password)) {
            $token = array(
                "iss" => "localhost",
                "aud" => "localhost",
                "iat" => time(),
                "exp" => time() + JWT_EXPIRATION_TIME,
                "data" => array(
                    "id" => $user->id,
                    "email" => $user->email,
                    "role" => $user->role
                )
            );

            $jwt = createJWT($token, JWT_SECRET_KEY, JWT_ALGORITHM);

            http_response_code(200);
            echo json_encode(array(
                "message" => "Successful login.",
                "jwt" => $jwt,
                "user" => array(
                    "id" => $user->id,
                    "email" => $user->email,
                    "lastname" => $user->lastname,
                    "firstname" => $user->firstname,
                    "role" => $user->role
                )
            ));
        } else {
            http_response_code(401);
            echo json_encode(array("message" => "Login failed."));
        }
    } else {
        http_response_code(401);
        echo json_encode(array("message" => "Login failed."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Login failed. Data is incomplete."));
}