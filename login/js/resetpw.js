$(document).ready(function(){

  $("#submit").click(function(){

    var userid = $("#userid").val();
    var password = $("#password1").val();
    var password2 = $("#password2").val();

    if((password == "") || (password2 == "")) {
        $("#message").fadeOut(0, function (){
              $(this).html("<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Please enter new password twice</div>").fadeIn();
        })
    } else if (userid == "") {

    }
    else {
      $.ajax({
        type: "POST",
        url: "ajax/resetformsubmit.php",
        data: "userid="+userid+"&password1="+password+"&password2="+password2,
        dataType: "json",
        success: function(json){

            if (json.status == true) {

                $('input').attr('disabled','disabled');

                $("#message").fadeOut(0, function (){
                  $(this).html(json.message).fadeIn();
                })
                $('#submit').hide();
            } else {

                $("#message").fadeOut(0, function (){
                  $(this).html(json.message).fadeIn();
                })
                $('#submit').show();

            }
        },
        beforeSend: function()
        {
            $("#message").fadeOut(0, function (){
                  $(this).html("<p class='text-center'><img src='images/ajax-loader.gif'></p>");
            });
        }
      });
    }
    return false;
  });
});
