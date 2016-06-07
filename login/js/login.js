$(document).ready(function(){

  $("#submit").click(function(){

    var username = $("#myusername").val();
    var password = $("#mypassword").val();

    if((username == "") || (password == "")) {
      $("#message").html("<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Please enter a username and a password</div>");
    }
    else {
      $.ajax({
        type: "POST",
        url: "checklogin.php",
        data: "myusername="+username+"&mypassword="+password,
		dataType: 'JSON',
        success: function(html){
		console.log(html.response + ' ' + html.username);
          if(html.response=='true') {
            window.location="../index.php";
			location.reload();
			return html.username;
          }
          else {
            $("#message").html(html.response);
          }
        },
		error: function(textStatus, errorThrown) {
                console.log('textStatus: ' + textStatus);
                console.log('errorThrown: ' + errorThrown);
            },
        beforeSend:function()
        {
          $("#message").html("<p class='text-center'><img src='images/ajax-loader.gif'></p>")
        }
      });
    }
    return false;
  });
});
