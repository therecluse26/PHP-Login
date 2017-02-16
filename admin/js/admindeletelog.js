function deleteLog(id, btnid){

    var uidJSON = "[" + JSON.stringify(id) + "]";

    $.ajax({
        type: "GET",
        url: "ajax/dellogajax.php",
        data: "uid="+ uidJSON,
        dataType: 'JSON',
        beforeSend: function () {
           $('#delbutton'+btnid).html("<p class='text-center'><img class='verloader' src='../login/images/load.gif'></p>");
            $('#delbutton'+btnid).prop('disabled', true);
        },
        success: function (json) {
            $('#delbutton'+btnid).closest('tr').css('background-color', '#d9534f');
            $('#delbutton'+btnid).closest('tr').fadeOut(400, function(){

                $(this).remove();

               $("#rowcounthid").text(parseInt($("#rowcounthid").text() - json.message));
                $("#rowcount").text(parseInt($("#rowcounthid").text() - json.message));


                if($(document).find("tr").length <= 1){
                    $("#userlist").remove();
                    location.reload();
                }
            });
        },
        error: function (textStatus, errorThrown) {
           $('#delbutton'+btnid).prop('disabled', false);
           console.log(textStatus);
           console.log(errorThrown);
        }
    });
};

function deleteSelectedLogs(){

    var searchIDs = $("#userlist input:checkbox:checked").map(function(){
      return $(this).val();
    }).get();

    var btnIDs = $("#userlist input:checkbox:checked").map(function(){
        return $(this).attr('id');
    }).get();

    var idsJSON = JSON.stringify(searchIDs);

    $.ajax({
        type: "GET",
        url: "ajax/dellogajax.php",
        data: "uid="+idsJSON+"&m=v",
        dataType: 'HTML',
        beforeSend: function () {

            $.each(btnIDs, function(index, btnid){
                $('#delbutton'+btnid).html("<p class='text-center'><img class='verloader' src='../login/images/load.gif'></p>");
                $('#delbutton'+btnid).prop('disabled', true);
            })
        },
        success: function (json) {

            $.each(btnIDs, function(index, btnid){
                $('#delbutton'+btnid).closest('tr').css('background-color', '#d9534f');

                $('#delbutton'+btnid).closest('tr').fadeOut(400, function(){

                    $(this).remove();

                   $("#rowcounthid").text(parseInt($("#rowcounthid").text() - json.message));


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
