function deleteUser(id, username, email, btnid){
    $.ajax({
        type: "GET",
        url: "ajax/delajax.php",
        data: "uid="+ id + "&username=" + username + "&email=" + email +"&m=del",
        dataType: 'HTML',
        beforeSend: function () {
            $('#delbutton'+btnid).html("<p class='text-center'><img class='verloader' src='../images/load.gif'></p>");
            $('#verbutton'+btnid).prop('disabled', true);
            $('#delbutton'+btnid).prop('disabled', true);
        },
        success: function (html) {
            $('#delbutton'+btnid).closest('tr').css('background-color', '#cc0000');
            $('#delbutton'+btnid).closest('tr').fadeOut(400, function(){

                $(this).remove();

                if($(document).find("tr").length <= 1){
                    $("#userlist").remove();
                    location.reload();
                }
            });

        },
        error: function (textStatus, errorThrown) {
            $('#delbutton'+btnid).html('Delete');
            $('#verbutton'+btnid).prop('disabled', false);
            $('#delbutton'+btnid).prop('disabled', false);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
};
