<?php
header('Content-Type: application/json');

include 'db.php'; // Подключение к базе данных
include 'inc/functions.php'; // Общие функции

$conn = getDbConnection(); // Получение подключения

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из POST запроса
    $id = $_POST['id'];
    $app = $_POST['app'] ?? '';
    $namespace = $_POST['namespace'] ?? '';
    $key = $_POST['key'] ?? '';
    $en = $_POST['en'] ?? '';
    $he = $_POST['he'] ?? '';
    $ru = $_POST['ru'] ?? '';
    $comment = $_POST['comment'] ?? '';
    $code = $_POST['code'] ?? '';

    // Подготовка и выполнение SQL запроса
    $sql = "UPDATE datatable SET app=?, namespace=?, base=?, en=?, he=?, ru=?, comment=?, code=? WHERE id=?";
    $stmt = $conn->prepare($sql);

    try {
        $stmt->execute([$app, $namespace, $key, $en, $he, $ru, $comment, $code, $id]);
        echo json_encode(["status" => "success", "message" => "Data updated successfully."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error updating data: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>