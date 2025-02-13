<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

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
    !empty($data->first_name) &&
    !empty($data->last_name) &&
    !empty($data->email)
) {
    $member->first_name = $data->first_name;
    $member->last_name = $data->last_name;
    $member->email = $data->email;
    $member->phone = $data->phone;
    $member->address = $data->address;
    $member->church_id = $data->church_id;
    $member->cgroup_id = $data->cgroup_id;
    $member->member_number = $data->member_number;
    $member->number_of_kids = $data->number_of_kids;
    $member->join_date = date('Y-m-d H:i:s');
    $member->status = isset($data->status) ? $data->status : 'unpaid'; // Set default value if not provided

    if ($member->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Member was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create member."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create member. Data is incomplete."));
}