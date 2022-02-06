<?php
    include_once 'header.php'
?>

  <section class='signup-form'>
    <h2>Log In</h2>
    <form class="auth-form" action="includes/login.inc.php" method="post">
      <input type="text" name="username" placeholder="Username">
      <input type="password" name="password" placeholder="Password">
      <button type="submit" name="submit">Log In</button>
    </form>
  </section>

<?php
    include_once 'footer.php'
?>
