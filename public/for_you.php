<?php

    include '../includes/db.php';
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];

     

    try {

        // gi zimame (fetch) podatocite za ocenkite na korisnikot od predmetite
        $query_avg_grade = "SELECT AVG(grade) AS average_grade FROM courses WHERE user_id = :user_id";
        $stmt = $conn->prepare($query_avg_grade);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $average_grade = $result['average_grade'] ?? 0;

        // gi zimame (fetch) podaocite za zadacite na korisnikot
        $query_assignments = "SELECT * FROM assignment WHERE user_id = :user_id";
        $stmt = $conn->prepare($query_assignments);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $assignments_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // gi zimame podatocite za zadacite na korisnikot od to-do listata
        $query_tasks = "SELECT task_title, description FROM task WHERE user_id = :user_id";
        $stmt = $conn->prepare($query_tasks);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $tasks_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // gi zimame podatocite za neprocitanite izvestuvanja na korisnikot
        // $query_notifications = "SELECT * FROM notification WHERE user_id = :user_id";
        // $stmt = $conn->prepare($query_notifications);
        // $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        // $stmt->execute();
        // $notifications_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // gi podgotvuvame podatocite za nivno prakanje do frontend-ot
        $data = [
            'average_grade' => number_format($average_grade, 2),
            'assignments' => $assignments_result,
            'tasks' => $tasks_result,
            // 'notifications' => $notifications_result,
        ];

        echo json_encode($data);    // gi prakame podgotvenite podatoci do frontend-ot

    } catch(PDOException $e) {

        // spravuvanje so greski
        echo json_encode(['error' => 'Грешка во базата на податоци: ' . $e->getMessage()]);

    }

?>