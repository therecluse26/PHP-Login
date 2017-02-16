$(document).ready(function(){
    $('#testdbuser').click( function(e) {

        e.preventDefault();
        var dbhost = $('#dbhost').val();
        var dbuser = $('#dbuser').val();
        var dbpw = $('#dbpw').val();

        $.ajax({
            method: 'POST',
            url: 'ajax/showgrants.php',
            data: {dbhost: dbhost, dbuser: dbuser, dbpw: dbpw},
            success: function(resp){

            console.log(resp);

            $('#dbuserresp').text(resp.data).css('color', 'green');

            if(resp.status == '1'){
                $('#dbuserresp').text(resp.data).css('color', 'green');

            } else {
                $('#dbuserresp').text(resp.data).css('color', 'red');

            }

            }
        })
    });
})
