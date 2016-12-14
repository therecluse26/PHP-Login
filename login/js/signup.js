$(document).ready(function () {

  $("#submitbtn").click(function () {

    var username = $("#newuser").val();
    var password = $("#password1").val();
    var password2 = $("#password2").val();
    var email = $("#email").val();

    if ((username == "") || (password == "") || (email == "")) {
      $("#message").fadeOut(0, function (){
        $(this).html("<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Please enter a username and a password</div>").fadeIn();
      })
    }
    else {
      $.ajax({
        type: "POST",
        url: "ajax/createuser.php",
        data: "newuser=" + username + "&password1=" + password + "&password2=" + password2 + "&email=" + email,
        success: function (html) {

          var text = $(html).text();
          //Pulls hidden div that includes "true" in the success response
          var response = text.substr(text.length - 4);

          if (response == 'true') {

            $('input').attr('disabled','disabled');

            $("#message").fadeOut(0, function (){
                $(this).html(html).fadeIn();
            })
            $('#submitbtn').hide();
            $('#orlogin').hide();

          }
          else {
            $("#message").fadeOut(0, function (){
                $(this).html(html).fadeIn();
            })
            $('#submitbtn').show();
            $('#orlogin').show();

          }
        },
        beforeSend: function () {
            $("#message").fadeOut(0, function (){
              $(this).html("<p class='text-center'><img src='images/ajax-loader.gif'></p>").fadeIn();
            })
        }
      });
    }
    return false;
  });
});
