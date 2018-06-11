$(document).ready(function (e) {

    //Ajax form submit
    $("#profileForm").submit(function (e) {

        if ($("#email").val() == $("#emailorig").val()) {
            $("#email").prop("disabled", true);
        }

        e.preventDefault();

        var formData = new FormData(this);

        formData.append("csrf_token", $('meta[name="csrf_token"]').attr("value"));

        $.ajax({
            url: "ajax/accountupdate.php",
            type: "POST",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (json) {

                $("#message").fadeOut(0, function () {
                    $(this).html(json.message).fadeIn();
                });

                if (json.status == true) {

                    $("#email").prop("disabled", false);
                    $("#emailorig").prop("disabled", false);
                    $("#emailorig").val($("#email").val());

                } else {

                    $("#email").prop("disabled", false);
                }
            },
            beforeSend: function () {

                $("#message").fadeOut(0, function () {
                    $(this).html("<p class='text-center'><img src='../login/images/ajax-loader.gif'></p>").fadeIn();
                });
            }
        });
    });
});
