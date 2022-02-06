<?php

if (isset($_POST["submit"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];

  require_once 'dbh.inc.php';
  require_once 'funcs.inc.php';

  if (emptyInput($username, $password) !== false) {
    header("location ../login.php");
    exit();
  }

  loginUser($conn, $username, $password);

} else {
  header("location ../login.php");
  exit();
}
