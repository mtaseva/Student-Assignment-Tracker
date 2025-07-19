<?php

include '../includes/db.php';
session_start();

// redirekcija ako korisnikot ne e najaven
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// dodavanje nov predmet
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $courseName = $_POST['course_name'];
    $semester = $_POST['semester'];
    $grade = $_POST['grade'];
    $ects = $_POST['ects'];
    $courseType = $_POST['course_type'];

    $stmt = $conn->prepare("INSERT INTO courses (course_name, semester, grade, ects, course_type, user_id) VALUES (:course_name, :semester, :grade, :ects, :course_type, :user_id)");
    $stmt->bindParam(':course_name', $courseName);
    $stmt->bindParam(':semester', $semester);
    $stmt->bindParam(':grade', $grade);
    $stmt->bindParam(':ects', $ects);
    $stmt->bindParam(':course_type', $courseType);
    $stmt->bindParam(':user_id', $userId);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Предметот е успешно додаден!';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Неуспешно додавање на предмет.';
        $_SESSION['message_type'] = 'error';
    }

    header("Location: ../views/courses.php");
    exit;
}

// brisenje predmet
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $courseId = $_POST['course_id'];

    $stmt = $conn->prepare("DELETE FROM courses WHERE course_id = :course_id AND user_id = :user_id");
    $stmt->bindParam(':course_id', $courseId);
    $stmt->bindParam(':user_id', $userId);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Предметот е успешно избришан!';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Неуспешно бришење на предмет.';
        $_SESSION['message_type'] = 'error';
    }

    header("Location: ../views/courses.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM courses WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['userId']);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
