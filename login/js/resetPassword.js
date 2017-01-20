$(document).ready(function(){
  "use strict";
  $("#submit").click(function(){

    var email = $("#email").val();

    if(email == "") {
      $("#message").html("<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Please enter an email address</div>");
    }else {
      $.ajax({
        type: "POST",
        url: "checkreset.php",
        data: "email="+email,
        success: function (html) {
            //console.log(html);

            $("#message").html(html);

        },
        error: function (textStatus, errorThrown) {
            console.log(textStatus);
            console.log(errorThrown);
        },
        beforeSend: function () {
            $("#message").html("<p class='text-center'><img src='images/ajax-loader.gif'></p>");
        }
      });
    }
    return false;
  });
});
