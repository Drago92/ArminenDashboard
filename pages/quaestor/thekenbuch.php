<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  echo "<script>top.window.location = '../../login.php'</script>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>K.St.V. Arminia | Thekenbuch</title>
  <link rel="icon" type="image/x-icon" href="assets/img/wappen.png">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../../css/styles.css">
</head>
<body class="hold-transition">
<?php
include("../../scripts/dbInvoice.php");
?>
<div id="addForm">
  <button type="button" id="addClose" class="close" data-dismiss="modal" aria-label="Close">
    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x-circle" fill="currentColor"
         xmlns="http://www.w3.org/2000/svg">
      <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
      <path fill-rule="evenodd"
            d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
    </svg>
  </button>
  <h4 id="t" class="font-weight-bold ml-5">Hinzufügen</h4>
  <form id="formAdd" action="thekenbuch.php" method="post">
    <div class="form-group row">
      <label for="inputVorname" >Vorname</label>
        <input id="inputVorname" type="text" name="Vorname"
               style="border: 1px solid #ccc; border-radius: 4px" class="form-control"/>
    </div>
    <div class="form-group row">
      <label for="inputNachname" >Nachname</label>
        <input id="inputNachname" type="text" name="Nachname"
               style="border: 1px solid #ccc; border-radius: 4px" class="form-control"/>
    </div>
    <div class="form-group row">
      <label for="inputBiername">Biername</label>
        <input id="inputBiername" type="text" name="Biername"
               style="border: 1px solid #ccc; border-radius: 4px" class="form-control"/>
    </div>
    <div class="row">
      <div class="col-8">
      </div>
      <div class="col-xs-4">
        <input id="inputId" name="Id" style="display:none;"/>
        <input type="submit" id="addBtn" style="display:none;" class="btn btn-primary" name="addPerson"
               value="Hinzufügen"/>
        <input type="submit" id="editBtn" style="display:none;" class="btn btn-primary" name="editPerson"
               value="Ändern"/>
      </div>
    </div>
  </form>
  <?php
  if (isset($_POST["addPerson"])) {
    $vorname = $_POST["Vorname"];
    $name = $_POST["Nachname"];
    $biername = $_POST["Biername"];
    if (!empty($name)) {
      $insert = mysqli_query($db, "INSERT INTO Thekenbuch(Name,Vorname,Biername) VALUES('$name','$vorname','$biername')");
      unset($_POST);
      echo "<meta http-equiv='refresh' content='0'>";
      exit;
    }
  }
  if (isset($_POST["editPerson"])) {
    $vorname = $_POST["Vorname"];
    $name = $_POST["Nachname"];
    $biername = $_POST["Biername"];
    $Id = $_POST["Id"];
    if (!empty($name)) {
      $sql = mysqli_query($db, "UPDATE Thekenbuch SET Name='$name', Vorname='$vorname', Biername='$biername'
        			WHERE Id=$Id");
      unset($_POST);
      echo "<meta http-equiv='refresh' content='0'>";
      exit;
    }
  }
  ?>
</div>
<div class="wrapper">
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Thekenbuch</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="thekenbuch" class="table dark-mode table-bordered table-striped">
              <thead class="">
              <tr>
                <th>Vorname</th>
                <th>Nachname</th>
                <th>Biername</th>
                <th>Bier</th>
                <th>Weizen</th>
                <th>Wasser</th>
                <th>Kasten</th>
                <th>Wein</th>
                <th>Pizza</th>
                <th>Anderes</th>
                <th></th>
                <th></th>
              </tr>
              </thead>
              <tbody>
              <?php

              mysqli_query($db, "SET NAMES 'utf8'");
              $ergebnis = mysqli_query($db, "SELECT * FROM `Thekenbuch` ORDER BY Name");
              while ($dsatz = mysqli_fetch_assoc($ergebnis)) {
                echo '<tr id="' . $dsatz["Id"] . '">';
                echo '<td>' . $dsatz["Vorname"] . '</td>';
                echo '<td>' . $dsatz["Name"] . '</td>';
                echo '<td>' . $dsatz["Biername"] . '</td>';
                echo '<td>' . $dsatz["Bier"] . '</td>';
                echo '<td>' . $dsatz["Weizen"] . '</td>';
                echo '<td>' . $dsatz["Wasser"] . '</td>';
                echo '<td>' . $dsatz["Kasten"] . '</td>';
                echo '<td>' . $dsatz["Wein"] . '</td>';
                echo '<td>' . $dsatz["Pizza"] . '</td>';
                echo '<td>' . $dsatz["Anderes"] . '</td>';
                echo '<td><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square edit" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="cursor: pointer">
					<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
					<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
				  </svg></td>';
                echo '<td><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="cursor: pointer">
                    <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>
                  </svg></td>';
                echo '</tr>';
              }

              ?>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
</div>
<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<script src="../../js/script.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#thekenbuch").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": [
        {
          text: 'Person hinzufügen',
          action: function (e, dt, node, config) {
            $('#addForm').show();
            $('#addBtn').show();
          }
        },
        {
          text: 'Rechnungen erstellen',
          action: function (e, dt, node, config) {
            alert('Button activated');
          }
        },
        {
          text: 'Alles auf 0 setzen',
          action: function (e, dt, node, config) {
            $.ajax({
              method: "POST",
              url: "../../scripts/reset.php",
            }).done(function (data) {
              window.location.reload();
            });
          }
        },
        "csv", "excel", "pdf", "print", "pageLength"]
    }).buttons().container().appendTo('#thekenbuch_wrapper .col-md-6:eq(0)');
  });
</script>
</body>
</html>
