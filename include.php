<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/var/log/errors.log');
header('Content-Type: application/json');
$passw = require_once __DIR__ . '/pass.php';
try {
    $dbh = new PDO('mysql:host=localhost;dbname=students', $passw['db_user'], $passw['db_pass']);
}catch (PDOException $e) {
    error_log("Ошибка подключения к БД " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка сервера']);
    exit;
}
