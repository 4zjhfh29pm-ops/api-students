<?php
global $dbh;
require_once __DIR__ . '/include.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try{
        $sql = "SELECT students.id, students.name, groups.id AS group_id, groups.name AS group_name 
FROM students LEFT JOIN students_groups ON students.id = students_groups.stud_id
LEFT JOIN groups ON students_groups.group_id = groups.id WHERE students.deleted =0 AND groups.deleted =0";
        $stmt = $dbh->query($sql);
        $students = $stmt->fetchAll();
        http_response_code(200);
        echo json_encode(['success' => $students]);
        exit;
    }catch (PDOException $e) {
        $log_message = "Ошибка получения данных " . $e->getMessage();
        file_put_contents('/var/log/errors.log', $log_message . "\n", FILE_APPEND);
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка сервера']);
        exit;
    }
}else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $input = file_get_contents('php://input');
        $data = json_decode($input);
        $name = $data['name'];
        $groupid = $data['group_id']
        $sql = "INSERT INTO students (name) VALUES ('$name')";
        $dbh = $dbh->exec($sql);
        $stud_id = $dbh->lastInsertId();
        if ($groupid !== null) {
            $sql1 = "INSERT INTO students_groups (stud_id, group_id) VALUES ('$stud_id', '$groupid')";
            $dbh = $dbh->exec($sql1);
        }
        http_response_code(201);
        echo json_encode(['success' => 'Студент создан']);
        exit;
    } catch (PDOException $e) {
        $log_message = "Ошибка создания студента " . $e->getMessage();
        file_put_contents('/var/log/errors.log', $log_message . "\n", FILE_APPEND);
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка сервера']);
        exit;
    }
}else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'];
    if ($id === null) {
        http_response_code(400);
        echo json_encode(['error' => 'Ошибка клиента ']);
        exit;
    }try{
        $sql = "UPDATE students SET deleted = 1 WHERE id = $id";
        $del = $dbh->exec($sql);
        if ($del>0){
            http_response_code(200);
            echo json_encode(['success'=>'Студент удален']);
        }else{
            http_response_code(404);
            echo json_encode(['error'=>'Студент не найден']);
            exit;
        }
    }catch (PDOException $e) {
        $log_message = "Ошибка удаления студента " . $e->getMessage();
        file_put_contents('/var/log/errors.log', $log_message . "\n", FILE_APPEND);
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка сервера']);
        exit;
    }
}else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $id = $_GET['id'];
    $input = file_get_contents('php://input');
    $data = json_decode($input);
    if ($id === null || $data === null) {
        http_response_code(400);
        echo json_encode(['error' => 'Ошибка клиента ']);
        exit;
    }
    $name = $data['name'];
    $groupid = $data['group_id'];
    try {
        $sql = "UPDATE students SET name = '$name' WHERE id = $id";
        $dbh = $dbh->exec($sql);
        $sql1 = "DELETE FROM students_groups WHERE stud_id = $id";
        $dbh = $dbh->exec($sql1);
        if ($groupid !== null) {
            $sql2 = "INSERT INTO students_groups (stud_id, group_id) VALUES ('$id', '$groupid')";
            $dbh = $dbh->exec($sql2);
        }
        http_response_code(200);
        echo json_encode(['success' => 'Студент обновлен']);
        exit;
    } catch (PDOException $e) {
        $log_message = "Ошибка обновления студента " . $e->getMessage();
        file_put_contents('/var/log/errors.log', $log_message . "\n", FILE_APPEND);
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка сервера']);
        exit;
    }
}else if ($_SERVER['REQUEST_METHOD'] === 'PATCh') {
    $id = $_GET['id'];
    $input = file_get_contents('php://input');
    $data = json_decode($input);
    if ($id === null || $data === null) {
        http_response_code(400);
        echo json_encode(['error' => 'Ошибка клиента ']);
        exit;
    }
    $name = $data['name'];
    $groupid = $data['group_id'];
    try {
        if($data['name']!== ''){
            $sql = "UPDATE students SET name = '$name' WHERE id = $id";
            $dbh = $dbh->exec($sql);
        }
        if($groupid !== null){
            $sql1 = "DELETE FROM students_groups WHERE stud_id = $id";
            $dbh = $dbh->exec($sql1);
            $sql2 = "INSERT INTO students_groups (stud_id, group_id) VALUES ('$id', '$groupid')";
            $dbh = $dbh->exec($sql2);
        }
        http_response_code(200);
        echo json_encode(['success' => 'Данные обновлены']);
        exit;
    } catch (PDOException $e) {
        $log_message = "Ошибка обновления данных " . $e->getMessage();
        file_put_contents('/var/log/errors.log', $log_message . "\n", FILE_APPEND);
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка сервера']);
        exit;
    }
}