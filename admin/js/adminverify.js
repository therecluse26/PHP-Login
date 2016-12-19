function verifyUser(id, email, username, btnid){

    var uidJSON = "[" + JSON.stringify(id) + "]";

    $.ajax({
        type: "GET",
        url: "ajax/verajax.php",
        data: "uid="+ uidJSON,
        dataType: 'HTML',
        beforeSend: function () {
           $('#verbutton'+btnid).html("<p class='text-center'><img class='verloader' src='../login/images/load.gif'></p>");
            $('#verbutton'+btnid).prop('disabled', true);
            $('#delbutton'+btnid).prop('disabled', true);
        },
        success: function (html) {
            $('#verbutton'+btnid).closest('tr').css('background-color', '#5cb85c');
            $('#verbutton'+btnid).closest('tr').fadeOut(400, function(){

                $(this).remove();

                if($(document).find("tr").length <= 1){
                    $("#userlist").remove();
                    location.reload();
                }
            });
        },
        error: function (textStatus, errorThrown) {
           $('#verbutton'+btnid).html('Verify');
           $('#verbutton'+btnid).prop('disabled', false);
           $('#delbutton'+btnid).prop('disabled', false);
           console.log(textStatus);
           console.log(errorThrown);
        }
    });
};
