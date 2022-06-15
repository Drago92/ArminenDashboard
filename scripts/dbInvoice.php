<?php
$servername = "thekenbuch.kstv-arminia.de";
$username = "d0287efb";
$password = "DrachenStark1863";
$dbname = "d0287efb";
$db = new mysqli($servername, $username, $password, $dbname);
if($db ->connect_error)
{
  exit("Verbindungsfehler: ".mysqli_connect_error());
}

?>
