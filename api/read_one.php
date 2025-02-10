<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

include_once '../config/database.php';
include_once '../models/Member.php';

$database = new Database();
$db = $database->getConnection();
$member = new Member($db);

$member->id = isset($_GET['id']) ? $_GET['id'] : die();

$member->readOne();

if ($member->first_name != null) {
    $member_arr = array(
        "id" => $member->id,
        "first_name" => $member->first_name,
        "last_name" => $member->last_name,
        "email" => $member->email,
        "phone" => $member->phone,
        "address" => $member->address,
        "status" => $member->status
    );

    http_response_code(200);
    echo json_encode($member_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Member does not exist."));
}