<?php
header('Content-Type: application/json');

include 'db.php'; // Database connection

$conn = getDbConnection(); // Getting the connection

$id = isset($_POST['id']) ? $_POST['id'] : null;

if ($id) {
    // Preparing the SQL query for deletion
    $stmt = $conn->prepare("DELETE FROM datatable WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Executing the query
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Data successfully deleted."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error deleting data."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ID was not provided."]);
}
?>