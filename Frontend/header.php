<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <title>Suntrayce Homepage</title>
  </head>

  <body class="body">
    <header>
      <nav>
        <ul>
            <?php
              if (isset($_SESSION["username"])) {
                echo "<li><a href='index.php'>Home</a></li>";
                echo "<li><a href='dashboard.php'>Dashboard</a></li>";
                echo "<li><a href='includes/logout.inc.php'>Log Out</a></li>";
              } else {
                echo "<li><a href='login.php'>Log in</a></li>";
                echo "<li><a href='signup.php'>Sign up</a></li>";
              }
            ?>
        </ul>
      </nav>
    </header>
