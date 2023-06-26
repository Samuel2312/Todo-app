<?php
  session_start();

  if (isset($_SESSION["user_id"])) {
    
    $mysqli = require __DIR__ . "/database.php";

    $sql = "SELECT * FROM user WHERE user_id = {$_SESSION["user_id"]}";
  
    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();

    $sort_by = "priority";

    if (isset($_POST["sort-by"])) {
      if ($_POST["sort-by"] == "priority") {
        $sort_by = "CASE
                      WHEN priority = 'high' THEN 1
                      WHEN priority = 'medium' THEN 2
                      WHEN priority = 'low' THEN 3
                      ELSE 4
                    END";
      } else if ($_POST["sort-by"] == "due-date") {
        $sort_by = "due_date";
      } else {
        $sort_by = "CASE
                  WHEN status = 'not-started' THEN 1
                  WHEN status = 'in-progress' THEN 2
                  WHEN status = 'completed' THEN 3
                  ELSE 4
                END";
      }
    }

    $sql_ = "SELECT * FROM task WHERE user_id = {$_SESSION["user_id"]} ORDER BY " . $sort_by;

    $result_ = $mysqli->query($sql_);

    if (isset($_GET["id"])) {
      
      $id = $_GET["id"];

      $delete = "DELETE FROM task WHERE task_id = $id";

      $mysqli->query($delete);

    }

  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Todo App</title>
    
    <link rel="stylesheet" href="style.css">
    <script>

      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth'
        });
        calendar.render();
      });

    </script>
</head>
<body>
    <?php if(isset($user)): ?>
    <div class="hero">
        <nav>
            <h1>Todo App</h1>
            <ul>
                <li><a href="#">Today</a></li>
                <li><a href="#">Calendar</a></li>
                <li><a href="#">Settings</a></li>
            </ul>
            <img src="user.jpg" alt="" class="user-pic" onclick="toggleMenu()">
            <div class="sub-menu-wrap" id="subMenu" >
                <div class="sub-menu">
                  <div class="user-info">
                    <img src="user.jpg" alt="">
                    <h3><?= htmlspecialchars($user["username"]) ?></h3>
                  </div>
                  <hr>
                  <a href="logout.php">
                    <p>Log out</p>
                  </a>
                </div>
              </div>
        </nav>
        <main>
            <section id="task-list">
              <h2>Task List</h2>
              <div class="filter-bar">
                <form action="" method="POST">
                  <label for="sort-by">Sort by:</label>
                  <select id ="sort-by" name="sort-by">
                    <option value="priority" <?php if(isset($_POST["sort-by"]) && $_POST["sort-by"] == "priority") {echo "selected";} ?>>Priority</option>
                    <option value="due-date" <?php if(isset($_POST["sort-by"]) && $_POST["sort-by"] == "due-date") {echo "selected";} ?>>Due Date</option>
                    <option value="status" <?php if(isset($_POST["sort-by"]) && $_POST["sort-by"] == "status") {echo "selected";} ?>>Status</option>
                  </select>
                  <button type="submit" id="but-sort">Sort</button>
                </form>
              </div>
              <table>
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Priority</th>
                    <th style="min-width: 100px;">Due Date</th>
                    <th>Status</th>
                    <th style="min-width: 100px;">Date Created</th>
                    <th style="min-width: 100px;">Notes</th>
                    <th>Delete</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- task rows will be dynamically added here -->
                  <?php 
                    if($result_->num_rows > 0) {
                      while($row = $result_->fetch_assoc()) {
                        echo "<tr><td>" . $row["task_title"] . 
                        "</td><td>" . $row["task_description"] . 
                        "</td><td>" . $row["priority"] . 
                        "</td><td>" . $row["due_date"] . 
                        "</td><td>" . $row["status"] . 
                        "</td><td>" . $row["create_date"] . 
                        "</td><td>" . $row["notes"] . 
                        "</td><td>" . "<a href='home.php?id=" . $row["task_id"] . "' style='background: rgb(92, 67, 67); color: #fff; padding: 5px 10px; text-decoration: none'>Delete</a>" . "</td></tr>";
                      }
                    }
                  ?>
                </tbody>
              </table>
             
            </section>
            <section id="task-detail">
              <h2>New Task</h2>
              <form action="task_detail.php" method="POST" id="new-task-form">
                <div>
                  <label for="title new-task-title">Title:</label>
                  <input type="text" id="title new-task-title" name="title">
                </div>
                <div>
                  <label for="description">Description:</label>
                  <textarea id="description" name="description"></textarea>
                </div>
                <div>
                  <label for="priority">Priority:</label>
                  <select id="priority" name="priority">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                  </select>
                </div>
                <div>
                  <label for="due-date new-task-due-date">Due Date:</label>
                  <input type="date" id="due-date new-task-due-date" name="due-date">
                  
                </div>
                <div>
                  <label for="status">Status:</label>
                  <select id="status" name="status">
                    <option value="not-started">Not Started</option>
                    <option value="in-progress">In Progress</option>
                    <option value="completed">Completed</option>
                  </select>
                </div>
                <div>
                  <label for="notes">Notes:</label>
                  <textarea id="notes" name="notes"></textarea>
                </div>
                <div>
                  <button type="submit">Save</button>
                  <button type="reset" id="delete-task">Delete</button>
                </div>
              </form>
            </section>
            <section id="quick-entry">
              <h2>Quick Entry</h2>
              <form action="quick_entry.php" method="POST">
                <div>
                  <label for="quick-title">Title:</label>
                  <input type="text" id="quick-title" name="quick-title" required>
                </div>
                <div>
                  <label for="quick-priority">Priority:</label>
                  <select id="quick-priority" name="quick-priority">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                  </select>
                </div>
                <div>
                  <label for="quick-due-date">Due Date:</label>
                  <input type="date" id="quick-due-date" name="quick-due-date">
                </div>
                <div>
                  <button class="btn" type="submit">Add Task</button>
                </div>
              </form>
            </section>
          <!--  <section id="calendar">
              <h2><div id="calender"> Calendar </div></h2>
              calendar will be dynamically added here 
            </section>-->
         
        </main>
        <footer>
            <p>&copy; 2023 Todo App</p>
        </footer>
    </div>
    

    <script>
        let subMenu = document.getElementById("subMenu");
    
        function toggleMenu() {
            subMenu.classList.toggle("open-menu");
        }
    </script>
    
    <?php else:
        header("Location: index.html");  
    ?>
    <?php endif; ?>
</body>
</html>