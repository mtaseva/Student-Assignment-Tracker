<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$flag=false;

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action']==='save'){
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $user_index = $_POST['user_index'];
    $bio = $_POST['bio'];
    $email = $_POST['email'];
    $date_of_birth = $_POST['date_of_birth'];


    $stmt = $conn->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, user_index = :user_index, bio = :bio, email = :email, date_of_birth= :date_of_birth  WHERE user_id = :user_id");
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':user_index', $user_index);
    $stmt->bindParam(':bio', $bio);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':date_of_birth', $date_of_birth);
    $stmt->bindParam(':user_id', $userId);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Податоците се успешно ажурирани!';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Неуспешно ажурирање на податоци.';
        $_SESSION['message_type'] = 'error';
    }

    
    header("Location: ../views/for_you.php?flag=$flag");
    exit;
    
} 

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action']==='update'){
    $flag = true;
} 
header("Location: ../views/for_you.php?flag=$flag");
