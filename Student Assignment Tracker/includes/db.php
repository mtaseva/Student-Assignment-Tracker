<?php

$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

// $host = 'localhost';
// $dbname = 'studentassigmenttracker1';
// $username = 'root';
// $password = 'usbw'; //smeni go voa u prazno ako koristesh XAMPP ako si usb web server usbw

try {

    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    die("Не е можна конекција со базата на податоци: " . $e->getMessage());
}

?>