<?php

function emptyInput($username, $password) {
  $result;
  if (empty($username) || empty($password)) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}


function uidExists($conn, $username) {
  $sql = "SELECT * FROM users WHERE usersName = ?;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../signup.php");
    exit();
  }

  mysqli_stmt_bind_param($stmt, "s", $username);
  mysqli_stmt_execute($stmt);

  $resultData = mysqli_stmt_get_result($stmt);
  if ($row = mysqli_fetch_assoc($resultData)) {
    return $row;
  } else {
    $result = false;
    return $result;
  }

  mysqli_stmt_close($stmt);
}

function createUser($conn, $username, $password) {
  $sql = "INSERT INTO users (usersName, usersPwd) VALUES (?, ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../signup.php");
    exit();
  }

  $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

  mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPwd);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  header("location: ../login.php");
  exit();
}

function loginUser($conn, $username, $password){
  $uidExists = uidExists($conn, $username);

  if(!$uidExists){
    header("location: ../login.php?error=UserDoesNotExist");
    exit();
  }

  $pwdHashed = $uidExists["usersPwd"];
  $checkPwd = password_verify($password, $pwdHashed);

  if ($checkPwd === false) {
    header("location: ../login.php?error=IncorrectPassword");
    exit();
  } else if ($checkPwd === true) {
    session_start();
    $_SESSION["userid"] = $uidExists["usersId"];
    $_SESSION["username"] = $uidExists["usersName"];
    header("location: ../dashboard.php");
    exit();
  }
}
