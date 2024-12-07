<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';
include 'inc/functions.php';

$conn = getDbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {   
    $app = $_POST['app'] ?? '';
    $namespace = $_POST['namespace'] ?? '';
    $key = $_POST['key'] ?? '';
    $en = $_POST['en'] ?? '';
    $he = $_POST['he'] ?? '';
    $ru = $_POST['ru'] ?? '';
    $comment = $_POST['comment'] ?? '';
    $code = $_POST['code'] ?? '';
    
    $sql = "INSERT INTO datatable (app, namespace, base, en, he, ru, comment, code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    try {
        $stmt->execute([$app, $namespace, $key, $en, $he, $ru, $comment, $code]);
        
        // Get the last inserted ID
        $lastId = $conn->lastInsertId();

        // Fetch the newly added data
        $stmt = $conn->prepare("SELECT * FROM datatable WHERE id = ?");
        $stmt->execute([$lastId]);
        $newData = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            "status" => "success",
            "message" => "Data added successfully.",
            "data" => $newData // Return the newly added data
        ]);
    } catch (PDOException $e) {
        logError($e->getMessage());
        echo json_encode([
            "status" => "error",
            "message" => "Error adding data: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method."
    ]);
}
?>