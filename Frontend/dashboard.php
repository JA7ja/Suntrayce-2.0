<?php
  if(!isset($_SESSION['username'])){
    header("location: index.php");
    exit();
  }
  include_once 'header.php';
?>


<?php
  include_once 'footer.php';
?>
