function newPwToggle() {
    if($("#amt").val() == null){
        $("#popupError").text("Bitte wähle ein Amt aus.");
    }else {
        $(".checkmark").hide();
        $("#newPwBtn").show();
        $("#popupText").text("Neues Passwort für " + $("#amt").val() + " beantragen?");
        $("#newPw").show();
    }
}

function newPw() {
    $(".checkmark").show();
    $("#newPwBtn").hide();
    $.ajax({
        method: "POST",
        url: "scripts/newPassword.php",
        data: { amt: $("#amt").val()}
    }).done(function() {
        $(".check").attr("class", "check check-complete");
        $(".fill").attr("class", "fill fill-complete");
        $(".check").attr("class", "check check-complete success");
        $(".fill").attr("class", "fill fill-complete success");
        $(".path").attr("class", "path path-complete");
        $("#popupText").text("Es wurde eine Email an den " + $("#amt").val() + " geschickt");
    });
}
//Delete
$(".bi-trash-fill").click(function () {
  let id = $(this).closest('tr').attr('id');
  $.ajax({
    method: "POST",
    url: "../../scripts/delete.php",
    data: {"id": id},
  }).done(function (data) {
    window.location.reload();
  });
})
$("#addClose").click(function () {
  $('#addForm').hide();
  $('#addBtn').hide();
  $('#editBtn').hide();
  $("#t").text("Hinzufügen");
  $("#inputVorname").val("");
  $("#inputNachname").val("");
  $("#inputBiername").val("");
})
$(".edit").click(function () {
  $("#t").text("Bearbeiten");
  $('#addForm').show();
  $('#editBtn').show();
  $("#inputVorname").val($(this).closest('tr').find('td:eq(0)').text());
  $("#inputNachname").val($(this).closest('tr').find('td:eq(1)').text());
  $("#inputBiername").val($(this).closest('tr').find('td:eq(2)').text());
  $("#inputId").val($(this).closest('tr').attr('id'));
})
$('#addForm').on('keyup keypress', function (e) {
  let keyCode = e.keyCode || e.which;
  if (keyCode === 13) {
    e.preventDefault();
    return false;
  }
})
