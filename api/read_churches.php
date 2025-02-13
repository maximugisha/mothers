<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

include_once '../config/database.php';
include_once '../models/Church.php';

$database = new Database();
$db = $database->getConnection();
$church = new Church($db);

$stmt = $church->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $churches_arr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $church_item = array(
            "id" => $id,
            "name" => $name
        );
        array_push($churches_arr, $church_item);
    }
    http_response_code(200);
    echo json_encode($churches_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No churches found."));
}
?>