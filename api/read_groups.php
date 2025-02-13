<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

include_once '../config/database.php';
include_once '../models/Cgroup.php';

$database = new Database();
$db = $database->getConnection();
$group = new Cgroup($db);

$stmt = $group->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $groups_arr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $group_item = array(
            "id" => $id,
            "name" => $name
        );
        array_push($groups_arr, $group_item);
    }
    http_response_code(200);
    echo json_encode($groups_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No groups found."));
}
?>