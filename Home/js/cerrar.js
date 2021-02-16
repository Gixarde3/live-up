window.addEventListener("beforeunload", function (e) {
  var confirmationMessage = "Saliendo";
  window.location="../../logout.php";
});
