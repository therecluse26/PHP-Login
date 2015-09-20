$(document).ready(function(){
    
  $("#submit").click(function(){

    	var username = $("#myusername").val();
	var password = $("#mypassword").val();
	var email = $("#myemail").val();
  var repwd = $("#retypepwd").val();
    
	
    
    if((email == "") || (password == "") || (username == "") ) {
      
      $("#message").html("<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Please enter all the fields</div>");
    }
    else {
      $.ajax({
        type: "POST",
        url: "createuser.php",
        data: "&myusername="+username+"&myemail="+email+"&mypassword="+password,
        success: function(html){    
          if(html=='true') {
            window.location="index.php";
          }
          else {
            $("#message").html(html);
          }
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
$('#retypepwd').on('keyup', function () {
    if ($(this).val() == $('#mypassword').val()) {
        document.getElementById("submit").disabled = false;
    } else{
       document.getElementById("submit").disabled = true;
       $('#message').html('Passwords don\'t match').css('color', 'red');
  }
});