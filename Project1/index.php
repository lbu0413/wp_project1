<!DOCTYPE html>
<html>
  <head>
    <title>Deal or No Deal</title>
    <link rel="stylesheet" type="text/css" href="main_style.css" />
  </head>
  <body>
    <!--Make the header sticky-->
    <header id="main-header">
      <img src="./assets/logo-large.png" alt="DEAL OR NO DEAL" />
      <div class="ticker-wrap">
        <div class="ticker">
          1. Jack Morris - 10000
          <?php
          // load leader-board file and print all in order
            ?>
        </div>
      </div>
    </header>
    <div class="content-body">
      <!-- Side scrolling leader-board along the top -->
      <!-- https://codepen.io/lewismcarey/pen/GJZVoG -->
      <h1>Play Now!</h1>
      <!-- Reveal hidden signin on click or hover -->
      <div id="hidden-box">
        <div id="hidden-signin-signup">
        Returning User?
        <!--
          Maybe add a cookie here to
          detect returning users or use session tokens 
        -->
        <a href="./signin.php">Login</a>
        <br /><br />
        New User?
        <a href="./signup.php">Sign up</a>
        </div>
      </div>
    </div>
  </body>
</html>
