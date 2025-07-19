<?php

include '../includes/db.php';
session_start();

// redirekcija ako korisnikot ne e najaven
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = (int)$_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'join') {
    $forum_id = (int)$_POST['forum_id'];
    $forumCode = 'abc';
    try {
        $sql = "INSERT INTO join_forum (user_join_id, forum_join_id, forum_code) VALUES (:user_id, :forum_id, :code)";
        $stmt = $conn->prepare($sql);
    
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':forum_id', $forum_id);
        $stmt->bindParam(':code', $forumCode);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Успешно стана член на групата!';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Неуспешен обид за станување член на група.';
            $_SESSION['message_type'] = 'error';
        }

        header("Location: ../views/forums.php");
        exit;
    } catch (PDOException $e) {
        echo "Query failed: " . $e->getMessage() . "<br>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'open') {
    $forum_id = (int)$_POST['forum_id'];
    
    header("Location: ../views/forum.php?forum_id=$forum_id");
    exit;

}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'exit') {
    $forum_id = (int)$_POST['forum_id'];

    $stmt = $conn->prepare("DELETE FROM join_forum WHERE forum_join_id = :forum_id AND user_join_id = :user_id");
    $stmt->bindParam(':forum_id', $forum_id);
    $stmt->bindParam(':user_id', $userId);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = 'Успешно ја напушти групата!';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Неуспешен обид за напуштање на групата.';
        $_SESSION['message_type'] = 'error';
    }

    header('Location: ../views/forums.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'addComment') {
    $forum_id = (int)$_POST['forum_id'];
    $content = $_POST['content'];
    $date = date('Y-m-d');
    try {
        $sql = "INSERT INTO forumpost (content, post_date, user_id, discussionforum_id) VALUES (:content, :post_date, :user_id, :discussionforum_id)";
        $stmt = $conn->prepare($sql);
    
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':post_date', $date);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':discussionforum_id', $forum_id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Успешно испраќање на порака!';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Неуспешно испраќање на порака.';
            $_SESSION['message_type'] = 'error';
        }    
    
        header("Location: ../views/forum.php?forum_id=$forum_id");
        exit;
    } catch (PDOException $e) {
        echo "Query failed: " . $e->getMessage() . "<br>";
        exit;
    }
    
    




}