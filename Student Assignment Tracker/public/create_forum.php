<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $title = $_POST['title'];
    $topic = $_POST['topic'];

    try {
        $sql = "INSERT INTO discussionforum (topic, title, user_creator_id, is_private) VALUES (:topic, :title, :creator, 1)";
        $stmt = $conn->prepare($sql);
        
        
        $stmt->bindParam(':topic', $topic);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':creator', $userId);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Групата е успешно креирана!';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Неуспешно креирање на група.';
            $_SESSION['message_type'] = 'error';
        }
        

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }


    header("Location: ../views/forums.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../views/styles/general.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Креирај група</title>
    <link rel="icon" type="image/x-icon" href="../assets/icon.ico">
</head>
<body>

<div class="main">
    <div class="navbar">
        <div class="icon">
            <a href="dashboard.html"><h2 class="logo">Студентски планер</h2></a>
        </div>

    <div class="content">

            <div class="form">
                        <div style="display:flex flex-flow:row  align-items: center; gap: 10px;">
                            <h2>Креирај група</h2>
                            <a href='../views/forums.php' style="text-decoration: none; color: black; font-weight: bold;"><button style="min-height:25px; max-height:25px;  min-width: 40px; max-width: 40px; border-radius: 10px;"><i class="fa-solid fa-arrow-left"></i></button></a>
                        </div>
                        <form action="" method="post" style="text-align: left">
                            <br><br><br><input type="hidden" name="action" value="create">
                            <input type="hidden" name="title" >

                            <div style="padding: 7px;">
                                <label>Име на групата:</label>
                                <input type="text" name="title" style="width: 220px;" required><br>
                            </div>

                            <div style="padding: 7px;">
                                <label>Тема:</label>
                                <textarea type="number" name="topic" required style="width: 300px;"></textarea><br>
                            </div>

                            <div style="padding: 7px;">
                                <label>Тип:</label>
                                <input type="text" name="is_public" value="Јавно" disabled style="color: black;"><br>
                            </div>

                            <button type="submit" class="button">Креирај</button>
                        </form>
                    </div>
                </div>
    
</div>

<footer style="text-align: center;
            padding: 1rem;
            background-color: rgba(0, 0, 0, 0.7);
            color: #D8C4B6;
            margin-top: auto;
            width: 100%;
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.5);
            position: fixed;
                 bottom: 0;
            ">
            © 2025 Планер за студенти. Сите права се задржани.
        </footer>

</body>
</html>






