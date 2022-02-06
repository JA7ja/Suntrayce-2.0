<?php

if (isset($_POST["submit"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];

  require_once 'dbh.inc.php';
  require_once 'funcs.inc.php';

  if (emptyInput($username, $password) !== false) {
    header("location ../signup.php");
    exit();
  }

  if(uidExists($conn, $username)) {
    header("location ../signup.php");
    exit();
  }

  createUser($conn, $username, $password);

} else {
  header("location ../signup.php");
  exit();
}
