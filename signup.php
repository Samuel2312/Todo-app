<?php

  if (strlen($_POST["password"]) < 8) {

    die("Password must contain atleast 8 characters");
    
  }

  $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

  $mysqli = require __DIR__ . "/database.php";

  $sql = "INSERT INTO user (username, email, password_hash) VALUES (?, ?, ?)";

  $stmt = $mysqli->stmt_init();

  if(! $stmt->prepare($sql)) {

    die ("sql error: " . $mysqli->error);

  }

  $stmt->bind_param ("sss", $_POST["username"], $_POST["email"], $password_hash);

  if($stmt -> execute()) {

    session_start();

    $sql = sprintf("SELECT * FROM user WHERE email = '%s'", $mysqli->real_escape_string($_POST["email"]));
    
    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();

    $_SESSION["user_id"] = $user["user_id"];

    header("Location: home.php");
    exit;

  } else {

    if ($mysqli->errno === 1062) {

        die("email already taken");

    } else {

        die($mysqli->error. " " . $mysqli->errno);

    }
    
  }

?>
