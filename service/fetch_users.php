<?php

require_once __DIR__ . "../../include/include.php";

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");

$users = db_select("SELECT * FROM user_tb");
$total = count($users);

if (!empty($_GET['fetch_users'])) {
    if ($total > 0) {
        echo json_encode(['data' => $users, 'rows' => $total, 'status' => true]);
        exit;
    }
    echo json_encode(['data' => $users, 'rows' => $total, 'status' => false]);
    exit;
}
