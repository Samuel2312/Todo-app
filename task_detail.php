<?php 

    session_start();

    $is_completed = 0;

    $date = date('Y-m-d');

    $user_id = $_SESSION["user_id"];

    $mysqli = require __DIR__ . "/database.php";

    $sql = "INSERT INTO task(task_title, task_description, due_date, priority, notes, user_id, create_date, is_completed, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $mysqli->stmt_init();

    if(! $stmt->prepare($sql)) {

        die ("sql error: " . $mysqli->error);

    }

    $stmt->bind_param ("sssssisis", $_POST["title"], $_POST["description"], $_POST["due-date"], $_POST["priority"], $_POST["notes"], $user_id, $date, $is_completed, $_POST["status"]);

    if($stmt -> execute()) {
        echo "<script>alert('Task added successfully!');</script>";
    } else {
        die($mysqli->error. " " . $mysqli->errno);   
    }
?>