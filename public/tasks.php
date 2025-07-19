<?php

include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $title = $data['title'];
    $description = $data['description'];
    $stmt = $conn->prepare("INSERT INTO task (task_title, description, /*status,*/ user_id) VALUES (?, ?, /*'Pending',*/ ?)");
    $stmt->execute([$title, $description, $userId]);

    // $notificationMessage = "Задачата '$title' е додадена!";
    // $stmt = $conn->prepare("INSERT INTO notification (notification_type, notification_date, message, user_id) VALUES ('Task', NOW(), ?, ?)");
    // $stmt->execute([$notificationMessage, $userId]);

    echo json_encode(['status' => 'success']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $taskId = $data['task_id'];
    $title = $data['title'];
    $description = $data['description'];

    $stmt = $conn->prepare("UPDATE task SET task_title = ?, description = ? WHERE task_id = ?");
    $stmt->execute([$title, $description, $taskId]);

    // $notificationMessage = "Задачата '$title' е ажурирана!";
    // $stmt = $conn->prepare("INSERT INTO notification (notification_type, notification_date, message, user_id) VALUES ('Task', NOW(), ?, ?)");
    // $stmt->execute([$notificationMessage, $userId]);

    echo json_encode(['status' => 'success']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $taskId = $data['task_id'];

    $stmt = $conn->prepare("DELETE FROM task WHERE task_id = ?");
    $stmt->execute([$taskId]);

    // $notificationMessage = "Задачата е избришана!";
    // $stmt = $conn->prepare("INSERT INTO notification (notification_type, notification_date, message, user_id) VALUES ('Task', NOW(), ?, ?)");
    // $stmt->execute([$notificationMessage, $userId]);

    echo json_encode(['status' => 'success']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    

    $stmt = $conn->prepare("SELECT * FROM task WHERE user_id = ?");
    $stmt->execute([$userId]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tasks);
    exit;
}
?>
