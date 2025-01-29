<?php

include '../includes/db.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $dob = $_POST['date_of_birth'];

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, date_of_birth) VALUES (:first_name, :last_name, :email, :password, :date_of_birth)");
    $stmt->bindParam(':first_name', $firstName);
    $stmt->bindParam(':last_name', $lastName);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':date_of_birth', $dob);

    if($stmt->execute()) {
        header("Location: ../views/authentication.html?status=success&action=register");
        exit;
    } else {
        header("Location: ../views/authentication.html?status=failure&action=register");
        exit;
    }

}

?>

<!-- <form method="post">

    <input type="text" name="first_name" placeholder="Име" required>
    <input type="text" name="last_name" placeholder="Презиме" required>
    <input type="email" name="email" placeholder="Е-маил" required>
    <input type="password" name="password" placeholder="Лозинка" required>
    <input type="date" name="date_of_birth" required>
    <button type="submit">Регистрирај се</button>

</form> -->