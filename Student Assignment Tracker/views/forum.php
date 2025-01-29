<?php
    include '../includes/db.php';
    session_start();
    // redirekcija ako korisnikot ne e najaven
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
    
    $userId = (int)$_SESSION['user_id'];
    $forum_id = (int)$_GET['forum_id'];

    $message = $_SESSION['message'] ?? '';
    $messageType = $_SESSION['message_type'] ?? '';
    unset($_SESSION['message'], $_SESSION['message_type']);
  
    $stmt = $conn->prepare("SELECT * FROM forumpost fp JOIN users u ON u.user_id=fp.user_id WHERE discussionforum_id=:forum_id ORDER BY forumpost_id");
    $stmt->bindParam(':forum_id', $forum_id);
    $stmt->execute();
    $allComents = $stmt->fetchAll(PDO::FETCH_ASSOC);
 

    $stmt = $conn->prepare("SELECT title FROM discussionforum WHERE discussionforum_id=:forum_id");
    $stmt->bindParam(':forum_id', $forum_id);
    $stmt->execute();
    $forumTitle = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['title'];

    // $stmt = $conn->prepare("SELECT first_name FROM users WHERE user_id=:user_id");
    // $stmt->bindParam(':user_id', $userId);
    // $stmt->execute();
    // $firstname = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['first_name'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форум за Дискусија - Chat</title>
    <link rel="icon" type="image/x-icon" href="../assets/icon.ico">
    <!-- <link rel="stylesheet" href="./styles/chat.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to top, #213555, #2b4b70, #3E5879, #D8C4B6);
            color: #fff;
            margin: 0;
        }
        
        .content{
            width: 80%;
            max-width: 900px;
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            overflow-y: auto;
            /* max-height: calc(100vh - 200px); */
        }

        .main {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            margin-top: 75px; 
            margin-top: 20px; 
            padding: 10px; 
            height: 100vh;
            min-height: calc(100vh - 40px); 
            
        }

        .comment {
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .comment.user {
            justify-content: flex-end;
        }

        .comment .content {
            background-color: #fff;
            color: #000;
            padding: 10px;
            border-radius: 10px;
            max-width: 70%;
        }

        .comment .info {
            font-size: 12px;
            color: #D8C4B6;
            margin-top: 5px;
        }

        textarea {
            width: 80%;
            height: 40px;
            margin-right: 10px;
            border: none;
            border-radius: 10px;
            padding: 10px;
            resize: none;
        }

        button {
            border: none;
            background-color: #D8C4B6;
            color: #000;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
            display: flex; 
            justify-content: center; 
            align-items: center; 
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #fff;
        }

    </style>
</head>
<body>

    <div class="main">

            <div style= "width: 90%; background-color:rgb(255, 255, 255); border-radius: 20px; padding: 10px 20px;
                        display: flex; justify-content: space-between;
                        align-items: center; margin-bottom: 20px;">
                <a href='./forums.php' style="text-decoration: none; color: black; font-weight: bold;"><button style="min-height:40px; max-height:25px;  min-width: 40px; max-width: 40px; border-radius: 10px border: none; background-color: #D8C4B6; cursor: pointer;"><i class="fa-solid fa-arrow-left"></i></button></a>
                <div style=" box-shadow: 5px 10px 20px #D8C4B6 inset; background-color:#D8C4B6; padding-left: 15px; padding-right: 15px; padding-top: 15px; padding-bottom: 15px; border-radius: 20px; font-weight: bold; "><?= htmlspecialchars($forumTitle); ?></div>
                <div style=" color: white;">ova e za estetika</div>
            </div>
        <div class="content" style="border-radius: 50px 50px 20px 20px; overflow-y: auto; min-height: 65vh;
                     max-height: 65vh;">

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
            
            <?php foreach($allComents as $comment):?>
                <?php if($comment['user_id'] == $userId): ?>
            <div style="display: flex; justify-content: flex-end; width: 100%; padding-top: 10px;">
                        <span style="display:flex; flex-direction: row; border-radius: 20px;">
                            <span style="font-weight: bold; padding-right: 7px; font-size: large; background-color: white; color: black; padding: 4px; border-radius: 20px 0px 5px 20px;"><?= htmlspecialchars($comment['content']); ?></span>
                            <span>
                                <div style="font-size: x-small; background-color: white; color: #031127; padding: 3px; padding-right: 4px; border-radius: 0px 20px 1px 0px; ">
                                    <?= htmlspecialchars($comment['post_date']); ?>
                                </div>
                                <div style="font-size: x-small; background-color: white; color: #031127; padding: 3px; padding-right: 4px; border-radius: 0px 1px 20px 5px; ">
                                <?= htmlspecialchars($comment['first_name']); ?>
                                </div>
                            </span> 
                        </span>
                    </div>
                <?php else: ?>
                    <div style="display: flex; justify-content: flex-end; width: 100%; padding-top: 10px;">


                    <span style="display:flex; flex-direction: row; border-radius: 20px;">
                             <span>
                                <div style="font-size: x-small; background-color: white; color: #031127; padding: 3px; padding-left: 6px; border-radius: 20px 0px 0px 1px; ">
                                    <?= htmlspecialchars($comment['post_date']); ?>
                                </div>
                                <div style="font-size: x-small; background-color: white; color: #031127; padding: 3px; padding-left: 6px; border-radius: 0px 1px 5px 20px; ">
                                <?= htmlspecialchars($comment['first_name']); ?>
                                </div>
                            </span> 
                            <span style="  font-weight: bold; padding-right: 7px; font-size: large; background-color: white; color: black; padding: 4px; border-radius: 0px 20px 20px 5px;"><?= htmlspecialchars($comment['content']); ?></span>
                        </span>

                
                    </div>
                <?php endif; ?>
             <?php endforeach; ?>
    </div><br>

        <div style="width: 100%; padding: 10px; border-radius: 0 0 20px 20px; position: relative; bottom: 0; left: 0;">
            <form action="../public/discusionforum.php" method="post" style="display: flex; justify-content: center; align-items: center; gap: 10px;">
                <textarea name="content" style="width: 55%; height: 40px; padding: 10px; border-radius: 12px; border: none;" placeholder="Внеси текст"></textarea>
                <input type="hidden" name="action" value="addComment">
                <input type="hidden" name="forum_id" value="<?= $forum_id; ?>">    
                <button type="submit" style="width: 40px; height: 40px; border-radius: 50%; border: none; background-color: #D8C4B6; cursor: pointer;">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
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