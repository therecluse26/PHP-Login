//Ajax form submit

/*$("#savebtn").click(function (e) {

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
*/
