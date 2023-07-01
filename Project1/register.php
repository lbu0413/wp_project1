<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register Page</title>
  <link rel="stylesheet" href="./styles.css">
</head>

<body class="register_body">
  <div class="register_header">
    <img src="./logo.png" alt="logo" class="logo">
    <h1>Register</h1>
  </div>
  <div class="register_form">
    <form action="signup_submit.php" method="post" class="register_form">
      <fieldset>
        <label for="username">Username
          <input type="text" name="username" size="16">
        </label>
        <label for="password">Password
          <input type="password" name="password" size="16">
        </label>
      </fieldset>
      <br>
      <br>
      <input type="submit" name="submit" value="submit">
    </form>
  </div>
</body>

</html>