<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

include_once '../config/database.php';
include_once '../models/Member.php';

$database = new Database();
$db = $database->getConnection();
$member = new Member($db);

// Get page and limit from query parameters
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;
$offset = ($page - 1) * $limit;

$stmt = $member->readPaginated($limit, $offset);
$num = $stmt->rowCount();

if ($num > 0) {
    $members_arr = array();

    $members_arr["pagination"] = array(
        "current_page" => $page,
        "per_page" => $limit,
        "total_records" => $member->count(),
        "total_pages" => ceil($member->count() / $limit)
    );
    $members_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $member_item = array(
            "id" => $id,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "phone" => $phone,
            "address" => $address,
            "join_date" => $join_date,
            "status" => $status
        );
        array_push($members_arr["records"], $member_item);
    }

    http_response_code(200);
    echo json_encode($members_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No members found."));
}