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

$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$userinfo = $stmt->fetchAll(PDO::FETCH_ASSOC);


$flag=false;
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['flag']) && $_GET['flag'] === '1'){
    $flag = true;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Страница за вас</title>
    <link rel="icon" type="image/x-icon" href="../assets/icon.ico">
    <link rel="stylesheet" href="./styles/profile.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
    <div class="main" style="padding-bottom: 50px;">
        <div class="navbar">
            <div class="icon">
                <a href="dashboard.html"><h2 class="logo">Студентски планер</h2></a>
            </div>
            <div class="menu">
                <ul>
                    <li><a href='./for_you.php' style="color: #070F2B">Профил</a></li>
                    <li><a href='./courses.php'>Предмети</a></li>
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

                <div class="form1" style="text-align: left; justify-content: center; flex-direction: column;">
                    <div>
                                <h2 style=" margin-top: 1ch;">Ваша персонализирана контролна табла</h2>

                            <?php if($flag == false): ?>
                                <div style=" margin-top: 1ch;">
                                    <label for="first_name">Име: </label>
                                    <input style="color: #000; margin-top: 1ch; width: 110px;" type="text" id="first_name" value="<?= htmlspecialchars($userinfo[0]['first_name']); ?>" disabled>
                                </div>
                                <div style=" margin-top: 1ch;">
                                    <label for="last_name">Презиме: </label>
                                    <input style="color: #000; margin-top: 1ch; width: 110px;" type="text" id="last_name" value="<?= htmlspecialchars($userinfo[0]['last_name']); ?>" disabled>
                                </div>
                                <div style=" margin-top: 1ch;">
                                    <label for="user_index">Индекс: </label>
                                    <input style="color: #000; margin-top: 1ch; width: 110px;" type="number" id="user_index" value="<?= htmlspecialchars($userinfo[0]['user_index']); ?>" disabled>
                                </div>
                                <div style=" margin-top: 1ch;">
                                    <label for="bio">Биографија: </label>
                                    <textarea style="color: #000; width: 300px; margin-top: 1ch;" name="bio" disabled><?= htmlspecialchars($userinfo[0]['bio']); ?></textarea>
                                </div>
                                <div style=" margin-top: 1ch;">
                                    <label for="email">Email: </label>
                                    <input style="color: #000; width: 300px; margin-top: 1ch;" type="text" id="email" value="<?= htmlspecialchars($userinfo[0]['email']); ?>" disabled>
                                </div>
                                <div style=" margin-top: 1ch;">
                                    <label for="date_of_birth">Дата на раѓање: </label>
                                    <input style="color: #000; width: 110px; margin-top: 1ch;" type="date" id="date_of_birth" value="<?= htmlspecialchars($userinfo[0]['date_of_birth']); ?>" disabled>
                                </div><br>
                                <form action="../public/update_user_data.php" method="post" style="display: inline;">
                                    <input type="hidden" name="action" value="update">    
                                    <button type="submit" class="button" style="border: 2px solid rgba(0, 0, 0, 0.5); font-size: ">Уреди</button>
                                </form>
                            <?php else: ?>
                            <form action="../public/update_user_data.php" method="post">
                                <div style=" margin-top: 1ch;">
                                    <label for="first_name">Име: </label>
                                    <input style="color: #000; margin-top: 1ch; width: 110px;" type="text" id="first_name" name='first_name' value="<?= htmlspecialchars($userinfo[0]['first_name']); ?>" >
                                </div>
                                <div style=" margin-top: 1ch;">
                                    <label for="last_name">Презиме: </label>
                                    <input style="color: #000; margin-top: 1ch; width: 110px;" type="text" id="last_name" name='last_name' value="<?= htmlspecialchars($userinfo[0]['last_name']); ?>" >
                                </div>
                                <div style=" margin-top: 1ch;">
                                    <label for="user_index">Индекс: </label>
                                    <input style="color: #000; margin-top: 1ch; width: 110px;" type="number" id="user_index" name='user_index' value="<?= htmlspecialchars($userinfo[0]['user_index']); ?>" >
                                </div>
                                <div style=" margin-top: 1ch;">
                                    <label for="bio">Биографија: </label>
                                    <textarea style="color: #000; width: 300px; margin-top: 1ch;" name="bio" ><?= htmlspecialchars($userinfo[0]['bio']); ?></textarea>
                                </div>
                                <div style=" margin-top: 1ch;">
                                    <label for="email">Email: </label>
                                    <input style="color: #000; width: 300px; margin-top: 1ch;" type="text" id="email"  name='email'value="<?= htmlspecialchars($userinfo[0]['email']); ?>" >
                                </div>
                                <div style=" margin-top: 1ch;">
                                    <label for="date_of_birth">Дата на раѓање: </label>
                                    <input style="color: #000; width: 120px; margin-top: 1ch;" type="date" id="date_of_birth" name='date_of_birth' value="<?= htmlspecialchars($userinfo[0]['date_of_birth']); ?>" >
                                </div>
                                <br>
                                <input type="hidden" name="action" value="save">
                                <button type="submit" class="button" style="border: 2px solid rgba(0, 0, 0, 0.5); font-size: ">Потврди</button>
                            </form>
                            <?php endif; ?>
                    </div>
                </div>



                <div class="form2" style="text-align: left; justify-content: center; ">

                    <div class="average-grade">
                        <h2>Вашата просечна оценка е: <span id="average-grade"></span></h2>
                        <canvas id="averageGradeChart" width="200" height="200"></canvas>
                    </div>

                </div>

                <div class="form3" style="text-align: left; justify-content: center; overflow-y: hidden;">
                    <h2>Завршени задачи:</h2>
                    
                    <div class="assignments" style="overflow-x: scroll;">
                        <div style="visibility: hidden; height: 10px; width: 500px; background-color: red;">
                        <!-- This content is hidden, but the div still takes up space -->
                    </div>
                        
                        <ul id="assignments-list" ></ul>
                    </div>

                </div>

                <!-- <div class="form3" style="text-align: left; justify-content: center; overflow-x: scroll;">
                <h2>Задачи кои се завршени</h2>

                    <div class="assignments" style="overflow-x: scroll;">
                    <div style="visibility: hidden; height: 10px; width: 500px; background-color: red;">
                        
                    </div>
                        <ul id="assignments-list" ></ul>
                    </div>

                </div> -->



                <!-- <div class="form4" style="text-align: left; justify-content: center;  overflow-x: scroll;">

                    <div class="tasks">
                        <h2>Ваши задачи</h2>
                        <ul id="tasks-list"></ul>
                    </div>

                </div> -->
                <!-- <div class="form4" style="text-align: left; justify-content: center; overflow-x: scroll;">
                    
                    <div class="notifications">
                        <h2>Нови известувања</h2>
                        <ul id="notifications-list"></ul>
                    </div>

                </div> -->

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
            padding: 15px;
            ">
            © 2025 Планер за студенти. Сите права се задржани.
        </footer>

        <script src="./javascript/for_you.js"></script>
    </div>
</body>
</html>