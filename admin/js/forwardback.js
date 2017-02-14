$(document).ready(function(){
    forwardBack('initial');
    $("#selectAll").change(function(){
        $("input:checkbox").prop('checked', $(this).prop("checked"));
        if ($("input:checkbox").is(":checked")) {
            $("input:checkbox").closest('tr').addClass("checkedrow");
        } else {
            $("input:checkbox").closest('tr').removeClass("checkedrow");
        }
    })
})

function forwardBack(direction){

    var limit = parseInt($("#limithid").text());
    var offset = parseInt($("#offsethid").text());
    var rowcount = parseInt($("#rowcounthid").text());
    var top = (offset + limit);

console.log("top ---> " + top);

    if (direction == 'right'){

        $("#leftbtn").attr('disabled', false);

        if (top >= rowcount) {
            console.log("top >= rowcount");
            top = rowcount;
            $("#rightbtn").attr('disabled', true);
        } else {
            console.log("ELSE top >= rowcount");
            top = top + limit;
            offset = (offset + limit);
        }

        $("#rowcount").html(rowcount);
        $("#pageCount").html(offset+"-"+ top +" of ");
        $("#offsethid").text(offset);

    } else if (direction == 'left') {

        $("#rightbtn").attr('disabled', false);

        if (offset <= 0) {
            console.log("offset <= 0");
            offset = 0;
            $("#leftbtn").attr('disabled', true);

        } else {
            console.log("ELSE offset <= 0");
            top = (offset);
            offset = (offset - limit);
        }

        $("#rowcount").html(rowcount);
        $("#pageCount").html(offset+"-"+ top +" of ");
        $("#offsethid").text(offset);

    } else if (direction == 'initial') {

        offset = 0;
        $("#rowcount").html(rowcount);
        $("#pageCount").html(offset+"-"+ top +" of ");
        $("#offsethid").text(offset);

    }

    console.log("top: " + top + " offset: " + offset + " limit: " + limit);

    $.ajax({
        type: "GET",
        url: "ajax/maillogajax.php",
        data: "limit="+limit+"&offset="+offset,
        dataType: 'HTML',
        beforeSend: function () {
           $('#mailLogOutput').html("<p class='text-center'><img class='verloader' src='../login/images/load.gif'></p>");
        },
        success: function (html) {

            $('#mailLogOutput').html(html);

        },

        error: function (textStatus, errorThrown) {

           console.log(textStatus);
           console.log(errorThrown);
        }
    });
};

function forwardBtn(){
    var limit = parseInt($("#limithid").text());
    var offset = parseInt($("#offsethid").text());
    var rowcount = parseInt($("#rowcounthid").text());
    var top = (offset + limit);

            $("#leftbtn").attr('disabled', false);

    if (top >= rowcount) {
        console.log("top >= rowcount");
        top = rowcount;
        $("#rightbtn").attr('disabled', true);
    } else {
        console.log("ELSE top >= rowcount");
        top = top + limit;
        offset = (offset + limit);
    }

    $("#rowcount").html(rowcount);
    $("#pageCount").html(offset+"-"+ top +" of ");
    $("#offsethid").text(offset);

    $.ajax({
        type: "GET",
        url: "ajax/maillogajax.php",
        data: "limit="+limit+"&offset="+offset,
        dataType: 'HTML',
        beforeSend: function () {
           $('#mailLogOutput').html("<p class='text-center'><img class='verloader' src='../login/images/load.gif'></p>");
        },
        success: function (html) {

            $('#mailLogOutput').html(html);

        },

        error: function (textStatus, errorThrown) {

           console.log(textStatus);
           console.log(errorThrown);
        }
    });
};

function backBtn(){
    var limit = parseInt($("#limithid").text());
    var offset = parseInt($("#offsethid").text());
    var rowcount = parseInt($("#rowcounthid").text());
    var top = (offset + limit);

 $("#rightbtn").attr('disabled', false);

        if (offset <= 0) {
            console.log("offset <= 0");
            offset = 0;
            $("#leftbtn").attr('disabled', true);

        } else {
            console.log("ELSE offset <= 0");
            top = (offset);
            offset = (offset - limit);
        }

        $("#rowcount").html(rowcount);
        $("#pageCount").html(offset+"-"+ top +" of ");
        $("#offsethid").text(offset);


    $.ajax({
        type: "GET",
        url: "ajax/maillogajax.php",
        data: "limit="+limit+"&offset="+offset,
        dataType: 'HTML',
        beforeSend: function () {
           $('#mailLogOutput').html("<p class='text-center'><img class='verloader' src='../login/images/load.gif'></p>");
        },
        success: function (html) {

            $('#mailLogOutput').html(html);

        },

        error: function (textStatus, errorThrown) {

           console.log(textStatus);
           console.log(errorThrown);
        }
    });
};
