<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $courseId = $_POST['course_id'];
    $courseName = $_POST['course_name'];
    $semester = $_POST['semester'];
    $grade = $_POST['grade'];
    $ects = $_POST['ects'];
    $courseType = $_POST['course_type'];

    $stmt = $conn->prepare("UPDATE courses SET course_name = :course_name, semester = :semester, grade = :grade, ects = :ects, course_type = :course_type WHERE course_id = :course_id AND user_id = :user_id");
    $stmt->bindParam(':course_name', $courseName);
    $stmt->bindParam(':semester', $semester);
    $stmt->bindParam(':grade', $grade);
    $stmt->bindParam(':ects', $ects);
    $stmt->bindParam(':course_type', $courseType);
    $stmt->bindParam(':course_id', $courseId);
    $stmt->bindParam(':user_id', $userId);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Предметот е успешно обновен!';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Неуспешно обновување на предмет.';
        $_SESSION['message_type'] = 'error';
    }

    header("Location: ../views/courses.php");
    exit;
}

if (isset($_GET['course_id'])) {
    $courseId = $_GET['course_id'];
    $stmt = $conn->prepare("SELECT * FROM courses WHERE course_id = :course_id AND user_id = :user_id");
    $stmt->bindParam(':course_id', $courseId);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        $_SESSION['message'] = 'Предметот не постои!';
        $_SESSION['message_type'] = 'error';
        header("Location: ../views/courses.php");
        exit;
    }
} else {
    header("Location: ../views/courses.php");
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
    <title>Уреди предмет</title>
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

        <div class="form">
            <div style="display:flex flex-flow:row  align-items: center; gap: 10px;">
            <h2>Уреди предмет</h2>
            <a href='../views/courses.php' style="text-decoration: none; color: black; font-weight: bold;"><button style="min-height:25px; max-height:25px;  min-width: 40px; max-width: 40px; border-radius: 10px;"><i class="fa-solid fa-arrow-left"></i></button></a>
        </div>
            <form action="../public/update_course.php" method="post" style="padding-top: 5px; text-align: left;">
                <br><br><br><input type="hidden" name="action" value="update">
                <input type="hidden" name="course_id" value="<?= htmlspecialchars($course['course_id']); ?>">

                <div  style="padding-top: 5px;">
                    <label>Име на предметот:</label>
                    <input type="text" name="course_name" value="<?= htmlspecialchars($course['course_name']); ?>" required><br>
                </div>
                <div  style="padding-top: 5px;">
                    <label>Оценка:</label>
                    <input type="number" name="grade" value="<?= htmlspecialchars($course['grade']); ?>" required><br>
                </div>
                <div  style="padding-top: 5px;">
                    <label>Број на кредити:</label>
                    <input type="number" name="ects" value="<?= htmlspecialchars($course['ects']); ?>" required><br>
                </div>
                <div  style="padding-top: 5px;">
                    <label>Семестар:</label>
                    <input type="number" name="semester" value="<?= htmlspecialchars($course['semester']); ?>" required><br>
                </div>
                <div  style="padding-top: 5px;">
                    <label>Тип на предмет:</label>
                    <select name="course_type" required>
                        <option value="Core" <?= $course['course_type'] === 'Core' ? 'selected' : ''; ?>>Задолжителен</option>
                        <option value="Elective" <?= $course['course_type'] === 'Elective' ? 'selected' : ''; ?>>Изборен</option>
                        <option value="OutOfStudentsProgram" <?= $course['course_type'] === 'OutOfStudentsProgram' ? 'selected' : ''; ?>>Надвор од програма</option>
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
