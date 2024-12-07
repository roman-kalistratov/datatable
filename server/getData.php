<?php
header('Content-Type: application/json');

include 'db.php'; // Подключение к базе данных
include 'inc/functions.php'; // Подключение функций

$conn = getDbConnection(); // Получение подключения

// Проверка, передан ли ID в запросе
if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Приведение ID к целому числу
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
    // Если ID не передан, возвращаем все данные
    $data = getData($conn);
    echo json_encode([
        "status" => "success",
        "data" => $data
    ]);
}
?>