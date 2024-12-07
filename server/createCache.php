<?php
header('Content-Type: application/json');
include 'db.php'; // Подключение к базе данных
include 'inc/functions.php'; // Функции getData и getDataById

$conn = getDbConnection(); // Установление соединения с БД

function createCacheFiles($conn) {
    $cacheDir = __DIR__ . '/cache'; // Директория для кеша

    // Убедимся, что директория существует
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }

    // Получение всех данных
    $allData = getData($conn);

    // Создание файла all_data.json
    $allDataJsonFile = $cacheDir . '/all_data.json';
    file_put_contents($allDataJsonFile, json_encode($allData, JSON_PRETTY_PRINT));

    // Разделение данных по языкам и создание файлов
    $languages = ['en', 'he', 'ru'];
    foreach ($languages as $lang) {
        $phpFile = $cacheDir . "/$lang.php";
        $jsFile = $cacheDir . "/$lang.js";

        // Создание PHP файла
        $phpContent = "<?php\nreturn " . var_export(array_column($allData, $lang, 'id'), true) . ";\n";
        file_put_contents($phpFile, $phpContent);

        // Создание JS файла
        $jsContent = "const translations = " . json_encode(array_column($allData, $lang, 'id'), JSON_PRETTY_PRINT) . ";";
        file_put_contents($jsFile, $jsContent);
    }

    return [
        "message" => "Cache files created successfully.",
        "files" => [
            'all_data.json' => $allDataJsonFile,
            'en.php' => $cacheDir . '/en.php',
            'en.js' => $cacheDir . '/en.js',
            'he.php' => $cacheDir . '/he.php',
            'he.js' => $cacheDir . '/he.js',
            'ru.php' => $cacheDir . '/ru.php',
            'ru.js' => $cacheDir . '/ru.js',
        ]
    ];
}

// Вызов функции создания кеша
try {
    $result = createCacheFiles($conn);
    echo json_encode(["status" => "success", "message" => $result['message'], "files" => $result['files']]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error creating cache: " . $e->getMessage()]);
}
?>