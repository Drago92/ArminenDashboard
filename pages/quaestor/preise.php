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
  <title>K.St.V. Arminia | Preise</title>
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
<body class="hold-transition dark-mode">
<?php
include("../../scripts/dbInvoice.php");
?>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <h3 class="card-header text-center font-weight-bold text-uppercase py-4">
        Preise
      </h3>
      <div class="card-body">
        <div id="table" class="table-editable">
          <table class="table dark-mode table-bordered table-striped">
            <thead>
            <tr>
              <th class="text-center">Bezeichnung</th>
              <th class="text-center">Wert</th>
            </tr>
            </thead>
            <tbody>
            <?php

            mysqli_query($db, "SET NAMES 'utf8'");
            $ergebnis = mysqli_query($db, "SELECT * FROM `Preise`");
            while ($dsatz = mysqli_fetch_assoc($ergebnis)) {
              echo '<tr>';
              echo '<td>' . $dsatz["Bezeichnung"] . '</td>';
              echo '<td ><input class="value" type="number" start-value="' . $dsatz["Preis"] . '" min="0.00" max="10000.00" step="0.01" value="' . $dsatz["Preis"] . '"/> €</td>';
              echo '</tr>';
            }
            ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
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
<script>
  $(function () {
    $(".value").on('blur', function () {
      let item = $(this);
      item.closest("td").removeClass("invalid");
      item.removeClass("invalid");
      item.closest("td").removeClass("valid");
      item.removeClass("valid");
      if (item.closest("td").has("span").length > 0) {
        item.closest("td").find("span").remove();
      }
      let value = item.val();
      value = value.replaceAll(",", ".")
      value = $.trim(value);
      if (item.attr('start-value') !== value) {
        let property = item.closest("tr").find("td").html();
        if (!isNaN(value) && (value.split(".").length < 2 || value.split(".")[1].length <= 2)) {
          $.ajax({
            method: "POST",
            url: "../../scripts/changePrice.php",
            data: {"Bezeichnung": property, "Preis": value},
          }).done(function (data) {
            item.closest("td").addClass("valid");
            item.addClass("valid");
            item.closest("td").append("<span title='Wert geändert'>&#x2705;</span>");
            item.attr('start-value', value);
          });
        } else {
          item.closest("td").addClass("invalid");
          item.addClass("invalid");
          if (item.has("span").length == 0) {
            item.closest("td").append("<span title='Ungültiger Wert'>&#9888;</span>");
          }
        }
      }
    })
  })
</script>
</body>
</html>
