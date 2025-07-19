<?php

include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// $id_assi = $_GET['assignment_id'];
$message = $_SESSION['message'] ?? '';
$messageType = $_SESSION['message_type'] ?? '';
unset($_SESSION['message'], $_SESSION['message_type']);

try {
    // Set the current date
    $curDate = date('Y-m-d');

    // Prepare the SQL query
    $stmt = $conn->prepare("UPDATE assignment SET status_of_assignment='Overdue' WHERE due_date < :curDate AND status_of_assignment <> 'Completed'");

    // Bind the current date to the query
    $stmt->bindParam(':curDate', $curDate);

    // Execute the query
    if ($stmt->execute()) {
    } else {
        echo "Failed to update the assignment status.";
    }
} catch (PDOException $e) {
    // Catch any exception and display the error message
    echo "Error: " . $e->getMessage();
}
$stmt = $conn->prepare("SELECT * FROM assignment WHERE user_id = :user_id ORDER BY due_date ASC");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $conn->prepare("SELECT * FROM courses WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/assigments.css">
    <link rel="stylesheet" href="./styles/cal.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <title>Задачи</title>
    <link rel="icon" type="image/x-icon" href="../assets/icon.ico">
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
                <li><a href='./assignments.php' style="color: #070F2B">Задачи</a></li>
                <li><a href='./todo.html'>То-Do Листа</a></li>
                <li><a href='./forums.php'>Дискусија</a></li>
                <li><a href='./notifications.php'>Известувања</a></li>
                <li><a href='./help.html'>Помош</a></li>
                <li><a href='./about_us.html'>За нас</a></li>
                <li><a href='authentication.html?status=success&action=logout'>Oдјави се</a></li>
            </ul>
        </div>
    </div>

    <div class="content">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                <?php if ($message && $messageType): ?>
                    Swal.fire({
                        title: '<?= htmlspecialchars($message); ?>',
                        icon: '<?= htmlspecialchars($messageType); ?>',
                        confirmButtonText: 'ОК'
                    });
                <?php endif; ?>
            });
        </script>

        <div class="form" style="text-align: left; justify-content: center;">
            <div>
                <div style="align-items: center;">
                    <h2>Внеси нова задача</h2>
                    <form style=" margin-top: 4ch;"  action="../public/assignments.php" method="post">
                        <input type="hidden" name="action" value="add">
                        <div style=" margin-top: 1ch;"><label>Наслов на задачата:</label>
                        <input type="text" name="assignment_title" required><br></div>
                        <div style=" margin-top: 1ch;"><label>Опис на задачата:</label>
                        <textarea name="description"></textarea><br></div>
                        <div style=" margin-top: 1ch;"><label>Предмет:</label>
                        <select name="course_id" style="width: 200px">
                            <option value="">-- Не е поврзано --</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= $course['course_id']; ?>"><?= htmlspecialchars($course['course_name']); ?></option>
                            <?php endforeach; ?>
                        </select><br></div>
                        <div style=" margin-top: 1ch;"><label>Приоритет:</label>
                        <select name="priority" style="width: 150px"required>
                            <option value="Low">Низок</option>
                            <option value="Medium">Среден</option>
                            <option value="High">Висок</option>
                        </select><br></div>
                        <div style=" margin-top: 1ch;"><label>Статус:</label>
                        <select name="status_of_assignment" style="width: 150px" required>
                            <option value="Pending">Во тек</option>
                        </select><br></div>
                        <div style=" margin-top: 1ch;"><label>Рок:</label>
                        <input type="date" name="due_date" required><br></div>
                        <div style=" margin-top: 1ch;"><button type="submit" name="button" class="button">Додади</button></div>
                    </form>
                </div>
            </div>
        </div>

        <div class="form1" style="text-align: left; justify-content: center; overflow-y: scroll;">
            <div>
                <div class="assignment_list">
                    <h2>Задачи</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Наслов</th>
                                <th>Опис</th>
                                <th>Рок</th>
                                <th>Приоритет</th>
                                <th>Статус</th>
                                <th>Предмет</th>
                                <th colspan='3'>Акции</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (count($assignments) !== 0):?>
                            <?php foreach ($assignments as $assignment): ?>
                                <tr>
                                <td><?= htmlspecialchars($assignment['assignment_title']); ?></td>
                                    <td><?= htmlspecialchars($assignment['description']); ?></td>
                                    <td><?= htmlspecialchars($assignment['due_date']); ?></td>
                                    <td><?= htmlspecialchars($assignment['priority']); ?></td>
                                    <td><?= htmlspecialchars($assignment['status_of_assignment']); ?></td>
                                    <td>
                                        <?php
                                        $courseName = '';
                                        foreach ($courses as $course) {
                                            if ($course['course_id'] == $assignment['course_id']) {
                                                $courseName = $course['course_name'];
                                                break;
                                            }
                                        }
                                        ?>
                                    <?= htmlspecialchars($courseName); ?>
                                    </td>
                                    <td>
                                    <?php if($assignment['status_of_assignment'] != 'Overdue' && $assignment['status_of_assignment'] !=  'Completed'):?>
                                        <form action="../public/update_assignment.php" method="get" style="display: inline;">
                                            <input type="hidden" name="assignment_id" value="<?= $assignment['assignment_id']; ?>">
                                            <button type="submit" class="button" style="border: 2px solid rgba(0, 0, 0, 0.5); width: 30px; height: 30px; display: flex; justify-content: center; align-items: center; "><i class="fa-solid fa-pen-to-square"></i></button>
                                        </form>
                                    </td>
                                    <?php else: ?>
                                            <button type="submit" class="button" style="border: 2px solid rgba(0, 0, 0, 0.5);  cursor: not-allowed; background-color:rgb(250, 124, 124); width: 30px; height: 30px; display: flex; justify-content: center; align-items: center; "><i class="fa-solid fa-x"></i></button>
                                    <?php endif; ?>
                            
                                    </td>
                                    <td>
                                    <form action="../public/assignments.php" method="post" style="display: inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="assignment_id" value="<?= $assignment['assignment_id']; ?>">
                                            <button type="submit" class="button" style="border: 2px solid rgba(0, 0, 0, 0.5); width: 30px; height: 30px; display: flex; justify-content: center; align-items: center; " onclick="return confirm('Дали сте сигурни?')"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    <?php if($assignment['status_of_assignment'] != 'Overdue' && $assignment['status_of_assignment'] !=  'Completed'):?>
                                        <td>
                                            <form action="../public/assignments.php" method="post" style="display: inline;">
                                                <input type="hidden" name="action" value="done">
                                                <input type="hidden" name="assignment_id" value="<?= $assignment['assignment_id']; ?>">
                                                <button type="submit" class="button" style="border: 2px solid rgba(0, 0, 0, 0.5); width: 30px; height: 30px; display: flex; justify-content: center; align-items: center;" onclick="return confirm('Дали сте сигурни?')"><i class="fa-solid fa-check"></i></button>
                                            </form>
                                        </td>
                                        <?php else: ?>
                                        <td>
                                            <button type="submit" class="button" style="border: 2px solid rgba(0, 0, 0, 0.5);  cursor: not-allowed; background-color:rgb(250, 124, 124); width: 30px; height: 30px; display: flex; justify-content: center; align-items: center; "><i class="fa-solid fa-x"></i></button>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan='9'>Нема задачи!!</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



        <div  class="form" style="text-align: left; justify-content: center; overflow-y: scroll;">
            <div>
                 
                <div class="wrapper">
                    <header>
                        <p class="current-date"></p>
                        <div class="icons">
                        <span id="prev" class="material-symbols-rounded">chevron_left</span>
                        <span id="next" class="material-symbols-rounded">chevron_right</span>
                        </div>
                    </header>
                    <div class="calendar">
                        <ul class="weeks">
                        <li>Нед</li>
                        <li>Пон</li>
                        <li>Вто</li>
                        <li>Сре</li>
                        <li>Чет</li>
                        <li>Пет</li>
                        <li>Саб</li>
                        </ul>
                        <ul class="days"></ul>
                    </div>
                    </div>
            
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
    
</div>
        <script src="./javascript/cal.js"></script>
</body>
</html>