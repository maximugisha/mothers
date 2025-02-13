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
    $member->status = isset($data->status) ? $data->status : 'unpaid';
    $member->church_id = $data->church_id;
    $member->cgroup_id = $data->cgroup_id;
    $member->next_of_kin = $data->next_of_kin;
    $member->number_of_kids = $data->number_of_kids;

    if ($member->update()) {
        http_response_code(200);
        echo json_encode(array(
            "message" => "Member was updated.",
            "member" => array(
                "id" => $member->id,
                "first_name" => $member->first_name,
                "last_name" => $member->last_name,
                "email" => $member->email,
                "phone" => $member->phone,
                "address" => $member->address,
                "status" => $member->status,
                "church_id" => $member->church_id,
                "cgroup_id" => $member->cgroup_id,
                "next_of_kin" => $member->next_of_kin,
                "member_number" => $member->member_number,
                "number_of_kids" => $member->number_of_kids
            )
        ));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update member."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update member. Data is incomplete."));
}
?>