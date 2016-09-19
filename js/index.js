$("#btn_register").click(function() {
    $("#loginDiv").hide();
    $("#registerDiv").show();
    $("#registerEmail").val('');
    $("#registerUsername").val('');
    $("#registerPassword").val('');
    $("#registerConfirmPassword").val('');
});

$("#btn_backLogin").click(function() {
   $("#registerDiv").hide();
   $("#loginDiv").show();
   $("#loginUsername").val('');
   $("#loginPassword").val('');
});