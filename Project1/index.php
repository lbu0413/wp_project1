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
        <div>1. Jack Morris - $100,000</div>
        <div>2. Wook Lee - $20,000</div>
        <?php
        //TODO: Load leader board from file and display top 10  
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
              <a href="./signin.html">Returning player?</a>
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