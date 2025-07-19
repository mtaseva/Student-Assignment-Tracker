<?php

include '../includes/db.php';
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// dodadi nova zadaca
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {

    $title = $_POST['assignment_title'];
    $description = $_POST['description'];
    $dueDate = $_POST['due_date'];
    $priority = $_POST['priority'];
    $status = $_POST['status_of_assignment'];
    $courseId = $_POST['course_id'];

    $stmt = $conn->prepare("INSERT INTO assignment (assignment_title, description, due_date, priority, status_of_assignment, user_id, course_id) VALUES (:title, :description, :due_date, :priority, :status, :user_id, :course_id)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':due_date', $dueDate);
    $stmt->bindParam(':priority', $priority);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':course_id', $courseId);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Задачата е успешно додадена!';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Неуспешно додавање на задача.';
        $_SESSION['message_type'] = 'error';
    }

    header("Location: ../views/assignments.php");
    exit;

}

// brisenje na zadacite (Delete)
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {

    $assignmentId = $_POST['assignment_id'];

    $stmt = $conn->prepare("DELETE FROM assignment WHERE assignment_id = :assignment_id AND user_id = :user_id");
    $stmt->bindParam(':assignment_id', $assignmentId);
    $stmt->bindParam(':user_id', $userId);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Задачата е успешно избришана!';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Неуспешно бришење на задача.';
        $_SESSION['message_type'] = 'error';
    }

    header("Location: ../views/assignments.php");
    exit;

}

$stmt = $conn->prepare("SELECT * FROM assignment WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $stmt = $conn->prepare("SELECT * FROM assignment WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($assignments);
    exit;
}


// postavuvawe na zadacata za zavrsena (Done)
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'done') {
    var_dump("assignment_id");
    $assignmentId = $_POST['assignment_id'];
    $status = 'Completed'; 

    $stmt = $conn->prepare("UPDATE assignment SET status_of_assignment = :status WHERE assignment_id = :assignment_id AND user_id = :user_id");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':assignment_id', $assignmentId);
    $stmt->bindParam(':user_id', $userId);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Задачата е успешно обновена!';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Неуспешно обновување на задача.';
        $_SESSION['message_type'] = 'error';
    }

    header("Location: ../views/assignments.php");
    exit;

}




?>