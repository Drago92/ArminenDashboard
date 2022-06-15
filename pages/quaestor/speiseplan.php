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
  <title>K.St.V. Arminia | Speiseplan</title>
  <link rel="icon" type="image/x-icon" href="assets/img/wappen.png">
  <!-- Google Font: Source Sans Pro -->
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- fullCalendar -->
  <link rel="stylesheet" href="../../plugins/fullcalendar/main.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../../css/styles.css">
</head>
<body class="hold-transition dark-mode">
<?php
include("../../scripts/dbInvoice.php");
?>

<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- /.col -->
        <div class="col-md-9">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Speiseplan</h3>
            </div>
            <div class="card-body p-0">
              <!-- THE CALENDAR -->
              <div id="calendar"></div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jQuery UI -->
<script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- fullCalendar 2.2.5 -->
<script src="../../plugins/moment/moment.min.js"></script>
<script src="../../plugins/fullcalendar/main.js"></script>
<!-- Page specific script -->
<script>
  $(function () {

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
      m    = date.getMonth(),
      y    = date.getFullYear()

    var Calendar = FullCalendar.Calendar;

    var calendarEl = document.getElementById('calendar');


    var calendar = new Calendar(calendarEl, {
      initialView: 'dayGridWeek',
      headerToolbar: {
        center: 'title'
      },
      selectable: true,
      weekNumbers: true,
      locale:'de',
      themeSystem: 'bootstrap',
      weekends: false,
      buttonText: {
        today:    'Heute',
      },
      defaultAllDay: true,
      events: [
        { // this object will be "parsed" into an Event Object
          title: 'The Title', // a property!
          start: '2022-05-09', // a property!
          end: '2022-05-09', // a property! ** see important note below about 'end' **
          resourceEditable: true
        }
      ],
      eventClick: function (event){
        $(event.el).find("div.fc-event-title").attr("contentEditable","true");
        // debugger

      }
      // dateClick: function(info) {
      //   // change the day's background color just for fun
      //   debugger
      //   info.dayEl.contentEditable=true;
      //   $(info.dayEl).find("div.fc-daygrid-day-frame").attr("contentEditable","true");
      //   $(info.dayEl).find("div.fc-daygrid-day-events").attr("contentEditable","true");
      //   $(info.dayEl).find("div.fc-daygrid-day-top").attr("contentEditable","true");
      //   $(info.dayEl).find("div.fc-daygrid-day-bg").attr("contentEditable","true");
      //   $(info.dayEl).find("div.fc-daygrid-day-bg").find("div").attr("contentEditable","true");
      //   $(info.dayEl).find("div.fc-daygrid-day-events").find("div").attr("contentEditable","true");
      // },
    });

    calendar.render();

    // $(".fc-next-button").click
  })
</script>
</body>
</html>
