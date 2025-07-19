<?php

include '../includes/db.php';
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['user_id'];
        header("Location: ../views/authentication.html?status=success&action=login");
        exit;

    } else {
        header("Location: ../views/authentication.html?status=failure&action=login");
        exit;
    }

}

?>

<!-- <form method="post">

    <input type="email" name="email" placeholder="Е-маил" required>
    <input type="password" name="password" placeholder="Лозинка" required>
    <button type="submit">Најави се</button>

</form> -->