<?php
function getData($conn) {
    try {
        $sql = "SELECT * FROM datatable ORDER BY id DESC";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching all data: " . $e->getMessage());
        return [];
    }
}

function getDataById($conn, $id) {
    try {
        $sql = "SELECT * FROM datatable WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching data by ID: " . $e->getMessage());
        return null;
    }
}

function logError($message) {
    file_put_contents('error_log.txt', date('Y-m-d H:i:s') . " - " . $message . PHP_EOL, FILE_APPEND);
}
?>