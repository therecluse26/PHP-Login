function verifyAll(){

    var searchIDs = $("#userlist input:checkbox:checked").map(function(){
      return $(this).val();
    }).get();

    var btnIDs = $("#userlist input:checkbox:checked").map(function(){
        return $(this).attr('id');
    }).get();

    var idsJSON = JSON.stringify(searchIDs);

    $.ajax({
        type: "GET",
        url: "ajax/verajax.php",
        data: "uid="+idsJSON+"&m=v",
        dataType: 'HTML',
        beforeSend: function () {

            $.each(btnIDs, function(index, btnid){
                $('#verbutton'+btnid).html("<p class='text-center'><img class='verloader' src='../login/images/load.gif'></p>");
                $('#verbutton'+btnid).prop('disabled', true);
                $('#delbutton'+btnid).prop('disabled', true);
            })
        },
        success: function (html) {

            $.each(btnIDs, function(index, btnid){
                $('#verbutton'+btnid).closest('tr').css('background-color', '#32CD32');

                $('#verbutton'+btnid).closest('tr').fadeOut(400, function(){

                    $(this).remove();

                    if($(document).find("tr").length <= 1){
                        $("#userlist").remove();
                    }
                })

            });

            location.reload();

        },
        error: function (textStatus, errorThrown) {

            location.reload();
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

$(document).ready(function(){
    $("#selectAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
        if ($("input:checkbox").is(":checked")) {
            $("input:checkbox").closest('tr').addClass("checkedrow");
        } else {
            $("input:checkbox").closest('tr').removeClass("checkedrow");
        }

    });

});
