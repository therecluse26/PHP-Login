$(document).ready(function(){
    forwardBack('initial');
})

function forwardBack(direction){

    var limit = parseInt($("#limithid").text());
    var offset = parseInt($("#offsethid").text());
    var rowcount = parseInt($("#rowcounthid").text());
    var top = (offset + limit);

    if (direction == 'initial') {

        offset = 0;
        $("#leftbtn").attr('disabled', true);
        $("#rowcount").html(rowcount);
        $("#pageCount").html(offset+"-"+ top +" of ");
        $("#offsethid").text(offset);

    }

    $.ajax({
        type: "GET",
        url: "ajax/maillogajax.php",
        data: "limit="+limit+"&offset="+offset,
        dataType: 'HTML',
        beforeSend: function () {
           $('#mailLogOutput').html("<p class='text-center'><img class='verloader' src='../login/images/load.gif'></p>");
        },
        success: function (html) {

            $('#mailLogOutput').html(html).promise().done(function(){

                $.getScript('js/selector.js');

            });
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
        top = rowcount;
        $("#rightbtn").attr('disabled', true);
    } else {
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

            $('#mailLogOutput').html(html).promise().done(function(){

                $.getScript('js/selector.js');

            });
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
        offset = 0;
        $("#leftbtn").attr('disabled', true);

    } else {
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
            $('#mailLogOutput').html(html).promise().done(function(){
                $.getScript('js/selector.js');
            });
        },

        error: function (textStatus, errorThrown) {

           console.log(textStatus);
           console.log(errorThrown);
        }
    });
};
