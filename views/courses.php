<?php

include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$messageType = $_SESSION['message_type'] ?? '';
unset($_SESSION['message'], $_SESSION['message_type']);

$stmt = $conn->prepare("SELECT * FROM courses WHERE user_id = :user_id AND (course_type='Elective' OR course_type='Core')");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$coursesCoreElective = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT * FROM courses WHERE user_id = :user_id AND course_type='OutOfStudentsProgram'");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$coursesOther = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/general.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Предмети</title>
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
                    <li><a href='./courses.php' style="color: #070F2B">Предмети</a></li>
                    <li><a href='./assignments.php'>Задачи</a></li>
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

            <div class="form" style="text-align: left; justify-content: center; ">
                <div>
                    <h2 style=" color:rgb(255, 255, 255); font-size: x-large; font-weight: bold;">Внеси ги предметите: </h2>
                    <div style="align-items: center;">
                        <form style=" margin-top: 5ch;" action="../public/courses.php" method="post">
                            <br><input type="hidden" name="action" value="add">
                            <div style=" margin-top: 1ch;"><label>Име на предметот:  </label><input type="text" name="course_name" placeholder="Име на предметот" required></div>
                            <div style=" margin-top: 1ch;"><label>Оценка:  </label><input type="text" name="grade" placeholder="Оценка" required></div>
                            <div style=" margin-top: 1ch;"><label>Број на кредити: </label><input type="number"  name="ects" placeholder="ECTS" required></div>
                            <div style=" margin-top: 1ch;"><label>Семестар:  </label><input type="number"  name="semester" placeholder="Семестар" required></div>
                            <div style=" margin-top: 1ch;"><label>Тип на предмет:  </label>
                            <select id="course_type" name="course_type" required>
                                <option value="Core">Задолжителен</option>
                                <option value="Elective">Изборен</option>
                                <option value="OutOfStudentsProgram">Надвор од програма</option>
                            </select></div>
                            <br><button type="submit" name="button" class="button">Додади</button>
                        </form>
                    </div>
                </div>
            </div>

            
            <div class="form" style="text-align: left; justify-content: center; overflow-y: scroll;">
            <div>
                <div class="course_list">
                    <h2>Предмети</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Име</th>
                                <th>Оценка</th>
                                <th>Кредити</th>
                                <th>Семестар</th>
                                <th>Тип</th>
                                <th colspan="2">Акции</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($coursesCoreElective) !== 0):?>
                            <?php foreach ($coursesCoreElective as $course): ?>
                                <tr>
                                    <td><?= htmlspecialchars($course['course_name']); ?></td>
                                    <td><?= htmlspecialchars($course['grade']); ?></td>
                                    <td><?= htmlspecialchars($course['ects']); ?></td>
                                    <td><?= htmlspecialchars($course['semester']); ?></td>
                                    <td><?= htmlspecialchars($course['course_type']); ?></td>
                                    <td>
                                        <form action="../public/update_course.php" method="get" style="display: inline;">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="course_id" value="<?= $course['course_id']; ?>">
                                            <button type="submit" class="button" style="border: 2px solid rgba(0, 0, 0, 0.5); width: 30px; height: 30px; display: flex; justify-content: center; align-items: center; "><i class="fa-solid fa-pen-to-square"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="../public/courses.php" method="post" style="display: inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="course_id" value="<?= $course['course_id']; ?>">
                                            <button type="submit" class="button" style="border: 2px solid rgba(0, 0, 0, 0.5); width: 30px; height: 30px; display: flex; justify-content: center; align-items: center; " onclick="return confirm('Дали сте сигурни?')"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan='6'>Нема предмети од студентската програма!!</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>


            
            <div class="form" style="text-align: left; justify-content: center; overflow-y: scroll;">
            <div>
                <div class="course_list">
                    <h2>Предмети</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Име</th>
                                <th>Оценка</th>
                                <th>Кредити</th>
                                <th>Семестар</th>
                                <th>Тип</th>
                                <th colspan="2">Акции</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($coursesOther) !== 0):?>
                            <?php foreach ($coursesOther as $course): ?>
                                <tr>
                                    <td><?= htmlspecialchars($course['course_name']); ?></td>
                                    <td><?= htmlspecialchars($course['grade']); ?></td>
                                    <td><?= htmlspecialchars($course['ects']); ?></td>
                                    <td><?= htmlspecialchars($course['semester']); ?></td>
                                    <td><?= htmlspecialchars($course['course_type']); ?></td>
                                    <td>
                                        <form action="../public/update_course.php" method="get" style="display: inline;">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="course_id" value="<?= $course['course_id']; ?>">
                                            <button type="submit" class="button" style="border: 2px solid rgba(0, 0, 0, 0.5); width: 30px; height: 30px; display: flex; justify-content: center; align-items: center; "><i class="fa-solid fa-pen-to-square"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="../public/courses.php" method="post" style="display: inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="course_id" value="<?= $course['course_id']; ?>">
                                            <button type="submit" class="button" style="border: 2px solid rgba(0, 0, 0, 0.5); width: 30px; height: 30px; display: flex; justify-content: center; align-items: center; " onclick="return confirm('Дали сте сигурни?')"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan='6'>Нема предмети надвор од студентската програма!!</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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

</body>
</html>
