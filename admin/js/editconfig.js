$(document).ready(function (e) {

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
    $("#settingsForm").submit(function (e) {

    e.preventDefault();

        $.ajax({
            url: "ajax/editconfigajax.php",
            type: "POST",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (json) {

                $("#message").fadeOut(0, function () {
                    $(this).html(json.message).fadeIn();

                    window.setInterval(function(){
                        $("#message").fadeOut();
                    }, 4000);
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
