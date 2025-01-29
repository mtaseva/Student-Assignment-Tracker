<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM courses WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {

    $assignmentId = $_POST['assignment_id'];
    $title = $_POST['assignment_title'];
    $description = $_POST['description'];
    $dueDate = $_POST['due_date'];
    $priority = $_POST['priority'];
    $status = $_POST['status_of_assignment'];
    $courseId = $_POST['course_id'];

    $stmt = $conn->prepare("UPDATE assignment SET assignment_title = :title, description = :description, due_date = :due_date, priority = :priority, status_of_assignment = :status, course_id = :course_id WHERE assignment_id = :assignment_id AND user_id = :user_id");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':due_date', $dueDate);
    $stmt->bindParam(':priority', $priority);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':course_id', $courseId);
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

if (isset($_GET['assignment_id'])) {
    $assignmentId = $_GET['assignment_id'];
    $stmt = $conn->prepare("SELECT * FROM assignment WHERE assignment_id = :assignment_id AND user_id = :user_id");
    $stmt->bindParam(':assignment_id', $assignmentId);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $assignment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$assignment) {
        $_SESSION['message'] = 'Задачата не постои!';
        $_SESSION['message_type'] = 'error';
        header("Location: ../views/assignments.php");
        exit;
    }
} else {
    header("Location: ../views/assignments.php");
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
    <title>Уреди задача</title>
    <link rel="icon" type="image/x-icon" href="../assets/icon.ico">
</head>
<body>

<div class="main">
    <div class="navbar">
        <div class="icon">
            <a href="dashboard.html"><h2 class="logo">Студентски планер</h2></a>
        </div>
        

    <div class="content">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                <?php if (isset($_SESSION['message']) && isset($_SESSION['message_type'])): ?>
                    Swal.fire({
                        title: '<?= htmlspecialchars($_SESSION['message']); ?>',
                        icon: '<?= htmlspecialchars($_SESSION['message_type']); ?>',
                        confirmButtonText: 'ОК'
                    });
                    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                <?php endif; ?>
            });
        </script>

        <div class="form" >
        <div style="display:flex flex-flow:row  align-items: center; gap: 10px;">
            <h2>Уреди задача</h2>
            <a href='../views/assignments.php' style="text-decoration: none; color: black; font-weight: bold;"><button style="min-height:25px; max-height:25px;  min-width: 40px; max-width: 40px; border-radius: 10px;"><i class="fa-solid fa-arrow-left"></i></button></a>
        </div>
            <form action="./update_assignment.php" method="post" style="padding-top: 5px; text-align: left;">
                <br><br><br><input type="hidden" name="action" value="update">
                <div  style="padding-top: 5px;">
                    <input type="hidden" name="assignment_id" value="<?= htmlspecialchars($assignment['assignment_id']); ?>">
                </div>
                <div  style="padding-top: 5px;">
                    <label>Наслов:</label>
                    <input type="text" name="assignment_title" style="width: 200px;" value="<?= htmlspecialchars($assignment['assignment_title']); ?>" required><br>
                </div>
                <div  style="padding-top: 5px;">
                    <label>Опис:</label>
                    <textarea name="description" style="width: 200px;"><?= htmlspecialchars($assignment['description']); ?></textarea><br>
                </div>
                <div  style="padding-top: 5px;">
                    <label>Рок:</label>
                    <input type="date" name="due_date" value="<?= htmlspecialchars($assignment['due_date']); ?>" required><br>
                </div>
                <div  style="padding-top: 5px;">    
                    <label>Приоритет:</label>
                    <select name="priority" required style="width: 90px;">
                        <option value="Low" <?= $assignment['priority'] === 'Low' ? 'selected' : ''; ?>>Низок</option>
                        <option value="Medium" <?= $assignment['priority'] === 'Medium' ? 'selected' : ''; ?>>Среден</option>
                        <option value="High" <?= $assignment['priority'] === 'High' ? 'selected' : ''; ?>>Висок</option>
                    </select><br>
                </div>
                <div  style="padding-top: 5px;">
                    <label>Статус:</label>
                    <select name="status_of_assignment" required style="width: 80px; color: black;" >
                        <option value="Pending" <?= $assignment['status_of_assignment'] === 'Pending' ? 'selected' : ''; ?>>Во тек</option>
                        <!-- <option value="Completed" <?= $assignment['status_of_assignment'] === 'Completed' ? 'selected' : ''; ?>>Завршена</option>
                        <option value="Overdue" <?= $assignment['status_of_assignment'] === 'Overdue' ? 'selected' : ''; ?>>Со поминат рок</option> -->
                    </select><br>
                </div>
                <div  style="padding-top: 5px;">    
                    <label>Предмет:</label>
                    <select name="course_id" required style="width: 100px;">
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['course_id']; ?>" <?= $assignment['course_id'] == $course['course_id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($course['course_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br>
                </div>
                <div  style="padding-top: 5px;">
                    <button type="submit" class="button">Обнови</button>
                </div>
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