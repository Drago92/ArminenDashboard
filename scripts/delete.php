<?php
include("dbInvoice.php");
$id= $_POST['id'];
$sql=mysqli_query($db,"DELETE FROM Thekenbuch WHERE Id = '".$id."'");
?>
