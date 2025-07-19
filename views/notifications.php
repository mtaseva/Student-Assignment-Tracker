<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Преземи порака за корисникот (ако постои)
$message = $_SESSION['message'] ?? '';
$messageType = $_SESSION['message_type'] ?? '';
unset($_SESSION['message'], $_SESSION['message_type']);

// Ажурирање на статус на задачи
try {
    $curDate = date('Y-m-d');
    $stmt = $conn->prepare("UPDATE assignment SET status_of_assignment='Overdue' WHERE due_date < :curDate AND status_of_assignment <> 'Completed'");
    $stmt->bindParam(':curDate', $curDate);
    $stmt->execute();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Преземање на задачи (рок помалку или еднаков на 5 дена)
$stmt = $conn->prepare("SELECT * FROM assignment WHERE user_id = :user_id AND status_of_assignment <> 'Completed' AND DATEDIFF(due_date, CURDATE()) BETWEEN 0 AND 5");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Преземање на предмети
$stmt = $conn->prepare("SELECT * FROM courses WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="mk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/notification.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Известувања</title>
    <link rel="icon" type="image/x-icon" href="../assets/icon.ico">
    <style>
    </style>
</head>
<body>
<div class="main">
    <div class="navbar">
        <div class="icon">
            <a href="dashboard.html"><h2 class="logo">Студентски планер</h2></a>
        </div>
        <div class="menu">
            <ul>
                <li><a href='./for_you.php'>Профил</a></li>
                <li><a href='./courses.php'>Предмети</a></li>
                <li><a href='./assignments.php' >Задачи</a></li>
                <li><a href='./todo.html'>To-Do Листа</a></li>
                <li><a href='./forums.php'>Дискусија</a></li>
                <li><a href='./notifications.php' style="color: #070F2B">Известувања</a></li>
                <li><a href='./help.html'>Помош</a></li>
                <li><a href='./about_us.html'>За нас</a></li>
                <li><a href='authentication.html?status=success&action=logout'>Одјави се</a></li>
            </ul>
        </div>
    </div>

    <div class="content">
        <div class="form" style="text-align: left; justify-content: center; flex-direction: column; overflow-y: scroll; max-width: 700px;
        min-width: 700px;">
            <div>
                <div class="table-container">
                <h2>Задачи со рок помалку од 5 дена</h2>
    
                    <div>
                        <?php if (!empty($assignments)): ?>
                            <?php foreach ($assignments as $assignment): ?>
                            <div class="form" style="text-align: left; justify-content: center; flex-direction: row; max-width: 500px;
                        min-width: 500px;  min-height: 130px; max-height: 130px;">

                                <table style="margin: 5px;" class="table">
                                    <thead>
                                        <tr>
                                            <th>Наслов:</th>
                                            <th>Статус:</th>
                                            <th>Рок:</th>
                                            <th>Приоритет:</th>
                                            <th>Оди до:</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                            <tr>
                                                <td><?= htmlspecialchars($assignment['assignment_title']); ?></td>
                                                <td><?= htmlspecialchars($assignment['status_of_assignment']); ?></td>
                                                <td><?= htmlspecialchars($assignment['due_date']); ?></td>
                                                <td><?= htmlspecialchars($assignment['priority']); ?></td>
                                        
                                                <td>
                                                <form action="../views/assignments.php" method="get" style="display:inline;">
                                                    <button type="submit" class="button" onclick="showAlert(event)"><a style="text-decoration: none;" href=''><i class="fa-solid fa-location-arrow"></a></i></button>
                                                </form>
                                                </td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan='5'>Нема задачи!!</td></tr>
                            <?php endif; ?>

                        </div>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function showAlert(event) {
        event.preventDefault(); 

        Swal.fire({
            title: 'Пренасочување кон делот за задачи!',
            text: 'Преглед на деталите за задачата.',
            icon: 'info', 
            confirmButtonText: 'Во ред'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = event.target.closest('form').action; 
            }
        });
    }
    </script>


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