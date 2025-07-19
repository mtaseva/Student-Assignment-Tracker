<?php
include './db.php';

function getTodaysTasks($user_id) {
    global $conn; 
    $today = date('Y-m-d');
    $stmt = $conn->prepare("SELECT * FROM task WHERE user_id = ? AND due_date = ?");
    $stmt->execute([$user_id, $today]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addTask($user_id, $title, $description, $due_date, $priority) {
    global $conn; 
    $stmt = $conn->prepare("INSERT INTO task (user_id, title, description, due_date, priority, status) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$user_id, $title, $description, $due_date, $priority, 'incomplete']);
}

function markTaskComplete($task_id) {
    global $conn; 
    $stmt = $conn->prepare("UPDATE task SET status = 'complete' WHERE id = ?");
    return $stmt->execute([$task_id]);
}
?>
