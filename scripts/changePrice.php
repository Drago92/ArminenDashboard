<?php
include("dbInvoice.php");
$bezeichnung=$_POST['Bezeichnung'];
$preis=$_POST['Preis'];
$prepare = mysqli_prepare($db, "UPDATE Preise SET Preis =?
            WHERE Bezeichnung = ?");
mysqli_stmt_bind_param($prepare, "ds",$preis,$bezeichnung);
mysqli_stmt_execute($prepare);
?>
