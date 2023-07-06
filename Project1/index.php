<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Deal or No Deal</title>
  <link rel="stylesheet" type="text/css" href="styles/main_style.css" />
  <link rel="stylesheet" type="text/css" href="styles/card_style.css" />
</head>

<body>
  <!--Make the header sticky-->
  <header id="main-header">
    <img src="./assets/logo-large.png" alt="DEAL OR NO DEAL" />
    <!-- Side scrolling leader-board along the top -->
    <!-- https://codepen.io/lewismcarey/pen/GJZVoG -->
    <div class="ticker-wrap">
      <div class="ticker">
          <?php
          require __DIR__."/common.php";
          $scores = read_leaderboard();
          foreach ($scores as $rank => $info) {
              echo "<div>".($rank+1).". ".$info[0]." - $".number_format((float)$info[1], 2)."</div>";
          }
            ?>
      </div>
    </div>
  </header>
  <div class="content-body">
    <div class="card-wrapper">
      <div class="card">
        <div class="card-front">
          <h1>PLAY NOW!</h1>
        </div>
        <div class="card-back inner-outline">
          <div>
            <p>
              <!--
                  Maybe add a cookie here to
                  detect returning users or use session tokens
                -->
              <a href="./cookie_check.php">Returning player?</a>
              <br><br>
              <a href="./register.html">First time?</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
