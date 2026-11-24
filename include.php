<?php
header('Content-Type: application/json');
$passw = require_once __DIR__ . '/pass.php';
try {
    $dbh = new PDO('mysql:host=localhost;dbname=students', $passw['db_user'], $passw['db_pass']);
}catch (PDOException $e) {
    $log_message = "Ошибка подключения БД " . $e->getMessage();
    file_put_contents('/var/log/errors.log', $log_message, FILE_APPEND);
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка сервера']);
    exit;
}
