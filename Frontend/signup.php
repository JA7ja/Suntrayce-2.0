<?php
    include_once 'header.php'
?>

  <section class='signup-form'>
    <h2>Sign Up</h2>
    <form action="includes/signup.inc.php" method="post">
      <input type="text" name="username" placeholder="Username">
      <input type="password" name="password" placeholder="Password">
      <button type="submit" name="submit">Sign Up</button>
    </form>
  </section>

<?php
    include_once 'footer.php'
?>
