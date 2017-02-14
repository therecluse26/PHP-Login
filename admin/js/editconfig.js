$(document).ready(function (e) {
    function intervalTrigger() {
        window.setInterval(function(){
        $("#message").fadeOut();
        }, 8000);
    }
    var trigid = intervalTrigger();

    //Test Email
    $("#testemail").click(function () {

        $.ajax({
            url: "ajax/testemailajax.php",
            type: "GET",
            data: "t=1",
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (json) {

                $("#message").fadeOut(0, function () {

                    if (json.status == 'true') {
                        var alertType = "success";
                    } else {
                        var alertType = "danger";
                    }

                    $(this).html("<div class=\"alert alert-"+alertType+" alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>"+json.message+"</div>").fadeIn();
                });
            },
            beforeSend: function () {

                window.clearInterval(trigid);

                $("#message").fadeOut(0, function () {
                    $(this).html("<p class='text-center'><img src='../login/images/ajax-loader.gif'></p>").fadeIn();
                });
            }
        });
    });

    //Instantiate empty FormData object
    var formData = new FormData();

    //Detect changes and append changes to FormData
    $("input").change(function(){
        formData.append(this.name, this.value);
    });

    $("select").change(function(){
        formData.append($(this).attr('name'), $(this).val());
    });

    $("textarea").change(function(){
        formData.append(this.name, this.value);
    });

    //Ajax form submit
    $("#savebtn").click(function (e) {

    e.preventDefault();

        $.ajax({
            url: "ajax/editconfigajax.php",
            type: "POST",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (json) {

                var trigid = intervalTrigger();

                $("#message").fadeOut(0, function () {
                    $(this).html(json.message).fadeIn();

                });

            },
            beforeSend: function () {

                $("#message").fadeOut(0, function () {
                    $(this).html("<p class='text-center'><img src='../login/images/ajax-loader.gif'></p>").fadeIn();
                });
            }
        });
    });

});
