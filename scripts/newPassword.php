<?php
include("dbconfig.php");
/**
 * @throws Exception
 */
function random_string() {
    if(function_exists('random_bytes')) {
        $bytes = random_bytes(16);
        $str = bin2hex($bytes);
    } else if(function_exists('openssl_random_pseudo_bytes')) {
        $bytes = openssl_random_pseudo_bytes(16);
        $str = bin2hex($bytes);
    } else if(function_exists('mcrypt_create_iv')) {
        $bytes = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
        $str = bin2hex($bytes);
    } else {
        //Bitte euer_geheim_string durch einen zufälligen String mit >12 Zeichen austauschen
        $str = md5(uniqid('euer_geheimer_string', true));
    }
    return $str;
}
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $statement = mysqli_prepare($link,"SELECT * FROM Admins WHERE Username = ?");
    mysqli_stmt_bind_param($statement, "s", $param_username);

    // Set parameters
    $param_username = $_POST["amt"];
    if(mysqli_stmt_execute($statement)) {
        $result = mysqli_stmt_get_result($statement);
        $user = mysqli_fetch_array($result);
        //Überprüfe, ob der User schon einen Passwortcode hat oder ob dieser abgelaufen ist
        $passwortcode = random_string();
        $statement = mysqli_prepare($link, "UPDATE Admins SET passwortcode = ?, passwortcode_time = NOW() WHERE Username = ?");
        mysqli_stmt_bind_param($statement, "ss", $param_pwcode,$param_username);

        // Set parameters
        $param_pwcode = sha1($passwortcode);
        $param_username = $_POST["amt"];
        if (mysqli_stmt_execute($statement)) {
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
            $url_passwortcode = 'http://localhost:63343/passwortzuruecksetzen.php?userid=' . $user['Amt'] . '&code=' . $passwortcode;
            $text = 'Hoher ' . $user['Amt'] . ',
            
für deinen Account fürs Arminendashboard wurde nach einem neuen Passwort gefragt. Um ein neues Passwort zu vergeben, rufe innerhalb der nächsten 24 Stunden die folgende Website auf:
' . $url_passwortcode . '
    
Solltest du dies nicht angefordert haben, so ignoriere diese E-Mail bitte.
    
MBuH
Dein Dashboard';

            mail($empfaenger, $betreff, $text, implode("\r\n",$headers));
        }
    }
}
echo true;
?>