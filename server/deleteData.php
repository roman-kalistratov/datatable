<?php
header('Content-Type: application/json');

include 'db.php'; // Подключение к базе данных

$conn = getDbConnection(); // Получение подключения

$id = isset($_POST['id']) ? $_POST['id'] : null;

if ($id) {
    // Подготовка SQL запроса на удаление
    $stmt = $conn->prepare("DELETE FROM datatable WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Выполнение запроса
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Данные успешно удалены."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Ошибка при удалении данных."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ID не был передан."]);
}
?>