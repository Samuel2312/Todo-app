<?php

  $is_invalid = false;

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $mysqli = require __DIR__ . "/database.php";
    
    $sql = sprintf("SELECT * FROM user WHERE email = '%s'", $mysqli->real_escape_string($_POST["email"]));
    
    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();
    
    
    if ($user) {

      if (password_verify($_POST["password"], $user["password_hash"])) {
        
        session_start();

        session_regenerate_id();
        
        $_SESSION["user_id"] = $user["user_id"];
        
        header("Location: home.php");
        exit;
      }

      $is_invalid = true;
    }
    
  }

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Page</title>
  <link rel="stylesheet" href="style1.css">

</head>
<body>

    <br><br><br><br><br><br><br>
    <div class="center">
        <h1>Welcome back</h1>
        <h3>Enter your email and password</h3>

        <?php if($is_invalid): ?>
          <em style="color: red">Email or password is incorrect.</em>
          <br><br>
        <?php endif; ?>

        <form method="POST">

            <input type="email" id="email" name="email" required placeholder="Email"><br><br>
    
            <input type="password" id="password" name="password" required placeholder="Password"><br><br>
    
            <input type="submit" value="Continue" class="button">
            
            <p>Don't have an account? <a href="signup.html">Sign up</a></p>

        </form>
    </div>
    <footer>
        <p>&copy; 2023 Todo App</p>
    </footer>
</body>
</html>
