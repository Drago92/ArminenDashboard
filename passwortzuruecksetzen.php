<?php
include("scripts/dbconfig.php");

if(!isset($_GET['userid']) && (!isset($_GET['code']) || !isset($_GET['abgelaufen']))) {
    die("Leider wurde beim Aufruf dieser Website kein Code zum Zurücksetzen deines Passworts übermittelt");
}

$statement = mysqli_prepare($link,"SELECT * FROM Admins WHERE Username = ?");
mysqli_stmt_bind_param($statement, "s", $userid);

// Set parameters
$userid = $_GET['userid'];
if(mysqli_stmt_execute($statement)) {
    $result = mysqli_stmt_get_result($statement);
    $user = mysqli_fetch_array($result);

    //Überprüfe dass ein Nutzer gefunden wurde
    if ($user === null) {
        die("Es wurde kein passender Benutzer gefunden");
    }

    if(!isset($_GET['abgelaufen'])) {
        $code = $_GET['code'];
        if ($user['passwortcode_time'] === null || strtotime($user['passwortcode_time']) < (time() - 24 * 3600)) {
            die("Dein Code ist leider abgelaufen");
        }
        //Überprüfe den Passwortcode
        if (sha1($code) != $user['passwortcode']) {
            die("Der übergebene Code war ungültig. Stell sicher, dass du den genauen Link in der URL aufgerufen hast.");
        }
    }else{
        // Initialize the session
        session_start();

        // Check if the user is logged in, if not then redirect him to login page
        if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
            header("location: login.php");
            exit;
        }
        $abgelaufen = $_GET['abgelaufen'];
    }

    //Der Code war korrekt oder sein Passwort ist abgelaufen, der Nutzer darf ein neues Passwort eingeben

    if (isset($_GET['send'])) {
        $passwort = $_POST['passwort'];
        $passwort2 = $_POST['passwort2'];

        if ($passwort != $passwort2) {
            $pw_err = "Bitte identische Passwörter eingeben";
        }else if(strlen($passwort)<8){
            $pw_err = "Das Passwort muss mindestens 8 Zeichen lang sein.";
        }else if(!preg_match('~[0-9]+~', $passwort)){
            $pw_err = "Das Passwort muss mindestens eine Zahl enthalten.";
        }else { //Speichere neues Passwort und lösche den Code
            $statement = mysqli_prepare($link, "UPDATE Admins SET Password = ?, passwortcode = NULL, passwortcode_time = NULL, Abgelaufen = 0 WHERE Username = ?");
            mysqli_stmt_bind_param($statement, "ss", $passworthash,$userid);
            $passworthash = password_hash($passwort, PASSWORD_DEFAULT);

            if (mysqli_stmt_execute($statement)) {
                if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
                    //TODO ändern vorm GoLive
                    //    $empfaenger = $user['Email'];
                    $empfaenger = 'k.broja@web.de';
                    $betreff = "Neues Passwort für deinen Account fürs Arminendashboard";
                    $from = "From: Ariminendashboard <noreply@kstv-Arminia.de>";
                    $headers   = array();
                    $headers[] = "MIME-Version: 1.0";
                    $headers[] = "Content-type: text/plain; charset=utf-8";
                    $headers[] = "From: {$from}";
                    //TODO ändern vorm GoLive
                    $text = 'Hoher ' . $user['Amt'] . ',

dein Passwort wurde erfolgreich geändert.

Solltest du dies nicht angefordert haben, fordere Bitte ein neues Passwort ein oder melde dich bei Drago ;)

MBuH
Dein Dashboard';

                    mail($empfaenger, $betreff, $text, implode("\r\n",$headers));
                    die("Dein Passwort wurde erfolgreich geändert");
                }else{
                    header("location: index.php");
                }
            }
        }
    }
}
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Arminendashboard | Neues Passwort</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/plugins/iCheck/square/blue.css">
    <!-- Eigene Klassen -->
    <link rel="stylesheet" href="/css/styles.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
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
            <?php
            if(!empty($abgelaufen)){
                echo '<div class="alert alert-default-info">Dein Passwort ist abgelaufen.</div>';
            }
            ?>
            <p class="login-box-msg">Neues Passwort vergeben</p>
            <form action="?send=1&amp;userid=<?php echo htmlentities($userid);  if(empty($abgelaufen)){?>&amp;code=<?php echo htmlentities($code); }else{?>&amp;abgelaufen=<?php echo htmlentities($abgelaufen); }?>" method="post">
                <div class="form-group form-control-feedback">
                    <span class="label">
                        Bitte gib ein neues Passwort ein:
                    </span>
                    <input type="password" class="form-control" placeholder="Password" name="passwort">
                </div>
                <div class="form-group form-control-feedback">
                    <span class="label">
                        Passwort erneut eingeben:
                    </span>
                    <input type="password" class="form-control" placeholder="Password" name="passwort2">
                </div>
                <div class="row">
                    <div class="col-6">
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat ">Passwort speichern</button>
                    </div>
                      <?php echo (!empty($pw_err)) ? '<div class="alert alert-danger mt-3">'.$pw_err.'</div>' : ''; ?>
                    <!-- /.col -->
                </div>
            </form>
        </div>
    </div>
</body>
