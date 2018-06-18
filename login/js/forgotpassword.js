$(document).ready(function () {

  $("#submitbtn").click(function () {

    var email = $("#email").val();

    if ((email == "")) {

        $("#message").fadeOut(0, function (){
            $(this).html("<div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Please enter an email</div>").fadeIn();
        });
    }
    else {
      $.ajax({
        type: "POST",
        url: "ajax/sendresetemail.php",
        data: "email=" + email,
        dataType: "JSON",
        success: function (json) {

        if(json.status === true){

            $("#message").fadeOut(0, function (){
                $(this).html("<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>"+json.message+"</div>").fadeIn();
            });
            $('#submitbtn').hide();

        } else {
            $("#message").fadeOut(0, function (){
                $(this).html("<div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>"+json.message+"</div>").fadeIn();
            });
        }
        },
        error: function(err){
          console.log(err);
              $("#message").fadeOut(0, function () {

                    if (err.responseJSON.status == 'true') {
                        var alertType = "success";
                    } else {
                        var alertType = "danger";
                    }

                    $(this).html("<div class=\"alert alert-"+alertType+" alert-dismissable\">\
                                  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">\
                                    &times;\
                                  </button>"+err.responseJSON.message+"</div>"
                                ).fadeIn();
                });
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
