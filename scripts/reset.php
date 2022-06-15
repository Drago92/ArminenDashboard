<?php
include("dbInvoice.php");
if(mysqli_query($db, "SELECT * FROM Thekenbuch WHERE Bier > 0 OR Weizen > 0 OR Wasser > 0 OR Kasten > 0 OR Wein > 0 OR Pizza > 0 OR Anderes > 0")){
  $backup = mysqli_query($db, "INSERT INTO Thekenbuch_Backup SELECT * FROM Thekenbuch
            WHERE Id >= 0");
}
$reset = mysqli_query($db, "UPDATE Thekenbuch SET Bier=0, Weizen=0, Wasser=0, Kasten=0, Wein=0,Pizza=0, Anderes=0
            WHERE Id >= 0");
?>
