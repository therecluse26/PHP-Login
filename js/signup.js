$(document).ready(function(){
    
  $("#submit").click(function(){

    var username = $("#newuser").val();
    var password = $("#password1").val();
    var email = $("#email").val();
    
    if((username == "") || (password == "") || (email == "")) {
      $("#message").html("<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Please enter a username and a password</div>");
    }
    else {
      $.ajax({
        type: "POST",
        url: "createuser.php",
        data: "newuser="+username+"&password1="+password+"&email="+email,
        success: function(html){    
          if(html == "true"){
			  
			$("#message").html(html);
			$(this).hide();
			  
		  }
		else {
			$("#message").html(html);
			$(this).show();

		}

        },
        beforeSend: function()
        {
          $("#message").html("<p class='text-center'><img src='images/ajax-loader.gif'></p>")
        }
      });
    }
    return false;
  });
});