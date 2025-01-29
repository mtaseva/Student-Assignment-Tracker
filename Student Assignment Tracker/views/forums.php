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

$stmt = $conn->prepare("SELECT * FROM discussionforum d JOIN users u ON u.user_id=d.user_creator_id");
$stmt->execute();
$alldiscussionforums = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT * FROM join_forum jf JOIN discussionforum df ON jf.forum_join_id=df.discussionforum_id JOIN users u ON u.user_id=df.user_creator_id WHERE jf.user_join_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$joinedForums = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Листа на групи</title>
    <link rel="icon" type="image/x-icon" href="../assets/icon.ico">
    <link rel="stylesheet" href="./styles/forums.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="main" style="padding-bottom: 50px;">
        <div class="navbar">
            <div class="icon">
                <a href="dashboard.html"><h2 class="logo">Студентски планер</h2></a>
            </div>
            <div class="menu">
                <ul>
                    <li><a href='./for_you.php'>Профил</a></li>
                    <li><a href='./courses.php'>Предмети</a></li>
                    <li><a href='./assignments.php'>Задачи</a></li>
                    <li><a href='./todo.html'>То-Do Листа</a></li>
                    <li><a href='./forums.php' style="color: #070F2B">Дискусија</a></li>
                    <li><a href='./notifications.php'>Известувања</a></li>
                    <li><a href='./help.html'>Помош</a></li>
                    <li><a href='./about_us.html'>За нас</a></li>
                    <li><a href='authentication.html?status=success&action=logout'>Oдјави се</a></li>
                </ul>
            </div>
        </div>

    <div class="content" style=" flex-direction: column;">

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

        <div class="form" style="text-align: left; justify-content: center; overflow-y: scroll;">
            <div>
                <div class="course_list">
                    <h2>Сите групи:</h2>
                    <table style="margin-top: 20px;">
                                <thead>
                                <tr>
                                    <th colspan='3' style="margin-top: 5px; color: lightblue;">Креирај група: </th>
                                    <th style="margin-top: 5px;">
                                        <a href='../public/create_forum.php'><button class="button" style="border: 2px solid rgba(0, 0, 0, 0.5); text-decoration: none;"><i class="fa-solid fa-plus"></i></button></a>
                                    </th>
                                </tr>
                                    <tr>
                                        <th>Име на групта:</th>
                                        <th>Тема</th>
                                        <th>Креирана од:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($alldiscussionforums) != 0):?>
                                    <?php foreach ($alldiscussionforums as $forum): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($forum['title']); ?></td>
                                            <td><?= htmlspecialchars($forum['topic']); ?></td>
                                            <td><?= htmlspecialchars($forum['first_name']); ?></td>
                                    
                                            <td>
                                                    <?php 
                                                    $alreadyJoined = false;

                                                    // Check if the user has already joined the forum
                                                    foreach ($joinedForums as $jforum) {
                                                        if ($jforum['forum_join_id'] === $forum['discussionforum_id']) {
                                                            $alreadyJoined = true;
                                                            break; // Exit the loop if a match is found
                                                        }
                                                    }

                                                    // Show the form only if the user hasn't joined
                                                    if (!$alreadyJoined): 
                                                    ?>
                                                        <form action="../public/discusionforum.php" method="post" style="display: inline;">
                                                            <input type="hidden" name="action" value="join">
                                                            <input type="hidden" name="forum_id" value="<?= $forum['discussionforum_id']; ?>">
                                                            <button type="submit" class="button" style="border: 2px solid rgba(0, 0, 0, 0.5); "><i class="fa-solid fa-right-to-bracket"></i></button>
                                                        </form>
                                                    <?php endif; ?>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan='4'>Нема групи!!</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                </div>
            </div>
        </div>




        <div class="form" style="text-align: left; justify-content: center; overflow-y: scroll;">
            <div>
                <div class="course_list">
                    <h2>Твои групи:</h2>
                    <table>
                            <thead >
                                <tr>
                                    <th>Име на групата</th>
                                    <th>Тема</th>
                                    <th>Создадена од:</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($joinedForums) != 0):?>
                                <?php foreach ($joinedForums as $forum): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($forum['title']); ?></td>
                                        <td><?= htmlspecialchars($forum['topic']); ?></td>
                                        <td><?= htmlspecialchars($forum['first_name']); ?></td>
                                        <td>
                                            <form action="../public/discusionforum.php" method="post" style="display: inline;">
                                                <input type="hidden" name="action" value="open">
                                                <input type="hidden" name="forum_id" value="<?= $forum['discussionforum_id']; ?>">
                                                <button type="submit" class="button" style="border: 2px solid rgba(0, 0, 0, 0.5);"><i class="fa-solid fa-comments"></i></button>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="../public/discusionforum.php" method="post" style="display: inline;">
                                                <input type="hidden" name="action" value="exit">
                                                <input type="hidden" name="forum_id" value="<?= $forum['discussionforum_id']; ?>">
                                                <button type="submit" class="button" style="border: 2px solid rgba(0, 0, 0, 0.5);"><i class="fa-solid fa-arrow-right-from-bracket"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan='5'>Нема групи во кои си ти!!</td></tr>
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