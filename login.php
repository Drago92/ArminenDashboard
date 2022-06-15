<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: index.php?user=".$_SESSION["username"]);
  exit;
}

include("scripts/dbconfig.php");

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])){

  // Check if username is empty
  if(empty($_POST["username"])){
    $username_err = "Bitte wähle dein Amt.";
  } else{
    $username = trim($_POST["username"]);
  }

  // Check if password is empty
  if(empty(trim($_POST["password"]))){
    $password_err = "Bitte gib ein Passwort ein.";
  } else{
    $password = trim($_POST["password"]);
  }

  // Validate credentials
  if(empty($username_err) && empty($password_err)){
    // Prepare a select statement
    $sql = "SELECT Username, Password, Abgelaufen FROM Admins WHERE Username = ?";

    if($stmt = mysqli_prepare($link, $sql)){
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "s", $param_username);

      // Set parameters
      $param_username = $username;

      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
        // Store result
        mysqli_stmt_store_result($stmt);

        // Check if username exists, if yes then verify password
        if(mysqli_stmt_num_rows($stmt) == 1){
          // Bind result variables
          mysqli_stmt_bind_result($stmt, $username, $hashed_password, $abgelaufen);
          if(mysqli_stmt_fetch($stmt)){
            if(password_verify($password, $hashed_password)){
              // Password is correct, so start a new session
              session_start();

              // Store data in session variables
              $_SESSION["loggedin"] = true;
              $_SESSION["username"] = $username;
              if($abgelaufen == 1){
                header("location: passwortzuruecksetzen.php?userid=$username&abgelaufen=Y");
              }else{
                // Redirect user to welcome page
                header("location: index.php?user=$username");
              }
            } else{
              // Password is not valid, display a generic error message
              $login_err = "Falsches Passwort";
            }
          }
        } else{
          // Username doesn't exist, display a generic error message
          $login_err = "Falscher Benutzername oder flasches Passwort";
        }
      } else{
        echo "Oops! Etwas ist schief gelaufen. Versuch es später wieder..";
      }

      // Close statement
      mysqli_stmt_close($stmt);
    }
  }

  // Close connection
//    mysqli_close($link);
}
?>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>K.St.V. Arminia | Login</title>
  <link rel="icon" type="image/x-icon" href="assets/img/wappen.png">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="/css/styles.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="index.php" class="row"><img src="assets/img/wappen.png" class="col-4" width="150">
      <div class="text-left col-8"><b>KStV </b>Arminia
        <p>zu Bonn im KV</p>
      </div>
    </a>
  </div>
  <!-- /.login-logo -->
  <div class="card-body login-card-body">
    <p class="login-box-msg">Log dich ein um zu starten</p>
    <?php
    if(!empty($login_err)){
      echo '<div class="alert alert-danger">' . $login_err . '</div>';
    }
    ?>
    <form action="login.php" method="post">
      <div class="form-group form-control-feedback">
        <div class="input-group-append">
          <select type="text" id="amt" name="username" value="username" class="custom-select">
            <option value="0" disabled hidden <?php echo (empty($username)) ? 'selected' : ''; ?>>Username</option>
            <?php
            $sql = "SELECT Username FROM Admins";
            $result = mysqli_query($link, $sql);
            while ($dsatz = mysqli_fetch_assoc($result)) {
              ?>
              <option value="<?= $dsatz["Username"]?>" <?php echo (strcmp( $dsatz["Username"],$username) == 0) ? 'selected' : ''; ?>><?= $dsatz["Username"] ?></option>
            <?php }
            mysqli_close($link);?>
          </select>
          <span class="input-group-text">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                </svg>
              </span>
        </div>
        <span class="text-red"><?php echo $username_err; ?></span>
      </div>
      <div class="form-group form-control-feedback">
        <div class="input-group-append">
          <input type="password" class="form-control" placeholder="Password" name="password"
          <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
          <span class="form-control-feedback input-group-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/>
                    </svg>
                </span>
        </div>
        <span class="text-red"><?php echo $password_err; ?></span>
      </div>
      <div class="row">
        <div class="col-8">
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" name="login" class="btn btn-primary btn-block btn-flat ">Einloggen</button>
        </div>
        <!-- /.col -->
      </div>
      <a href="#" onclick="newPwToggle()">Neues Passwort beantragen</a><br>
      <span id="popupError" class="text-red"></span>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<div class="modal" tabindex="-1" role="dialog" id="newPw">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Neues Passwort</h5>
        <button type="button" class="close" onclick="$('#newPw').hide()" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>
      <div class="modal-body">
        <p id="popupText"></p>
      </div>
      <div class="modal-footer">
        <div class="checkmark" style="display: none">
          <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" x="0px" y="0px"
               viewBox="0, 0, 100, 100" id="checkmark">
        <g transform="">
          <circle class="path" fill="none" stroke="#7DB0D5" stroke-width="4" stroke-miterlimit="10" cx="50" cy="50" r="44"/>
          <circle class="fill" fill="none" stroke="#7DB0D5" stroke-width="4" stroke-miterlimit="10" cx="50" cy="50" r="44"/>
          <polyline class="check" fill="none" stroke="#7DB0D5" stroke-width="8" stroke-linecap="round" stroke-miterlimit="10"
                    points="70,35 45,65 30,52  "/>
        </g>
            </svg>

        </div>
        <button type="button" class="btn btn-primary" id="newPwBtn" onclick="newPw()">
          Beantragen
        </button>
        <button type="button" class="btn btn-danger" onclick="$('#newPw').hide()">Abbrechen</button>
      </div>
    </div>
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/adminlte.min.js"></script>
<script src="/js/script.js"></script>
</body>
</html>
