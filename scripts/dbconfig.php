<?php
$servername = "quadratdesign.de";
$username = "d018e5c0";
$password = "lossenk8553113bn";
$dbname = "d018e5c0";
$link = new mysqli($servername, $username, $password, $dbname);
if($link ->connect_error)
{
    exit("Verbindungsfehler: ".mysqli_connect_error());
}

?>