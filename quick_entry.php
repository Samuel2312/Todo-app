<?php 

session_start();

$is_completed = 0;

$date = date('Y-m-d');

$user_id = $_SESSION["user_id"];

$mysqli = require __DIR__ . "/database.php";

$sql = "INSERT INTO task(task_title, priority, due_date, create_date, is_completed, user_id) VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->stmt_init();

if(! $stmt->prepare($sql)) {

    die ("sql error: " . $mysqli->error);

}

$stmt->bind_param("ssssii", $_POST["quick-title"], $_POST["quick-priority"], $_POST["quick-due-date"], $date, $is_completed, $user_id);

if($stmt -> execute()) {
    die("tasks added successfully");
} else {
    die($mysqli->error. " " . $mysqli->errno);   
}

?>