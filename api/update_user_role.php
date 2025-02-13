<?php
session_start();
include_once '../config/database.php';
include_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

$user->id = $data->id;
$user->role = $data->role;

if ($user->updateRole()) {
    echo json_encode(array("success" => true));
} else {
    echo json_encode(array("success" => false));
}
?>