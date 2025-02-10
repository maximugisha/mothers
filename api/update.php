<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Member.php';

$database = new Database();
$db = $database->getConnection();
$member = new Member($db);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->id) &&
    !empty($data->first_name) &&
    !empty($data->last_name) &&
    !empty($data->email)
) {
    $member->id = $data->id;
    $member->first_name = $data->first_name;
    $member->last_name = $data->last_name;
    $member->email = $data->email;
    $member->phone = $data->phone;
    $member->address = $data->address;
    $member->status = isset($data->status) ? $data->status : 'active';

    if ($member->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Member was updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update member."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update member. Data is incomplete."));
}