<?php
header('Content-Type: application/json');

include 'db.php';
include 'inc/functions.php';

$conn = getDbConnection();

// Checking if ID is provided in the request
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $data = getDataById($conn, $id);

    if ($data) {
        echo json_encode([
            "status" => "success",
            "data" => $data
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Data not found for ID: $id"
        ]);
    }
} else {
    // If ID is not provided, return all data
    $data = getData($conn);
    echo json_encode([
        "status" => "success",
        "data" => $data
    ]);
}
?>