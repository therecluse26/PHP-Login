$(document).ready(function(){

/*    $('#dbform').validate({
        rules: {
            dbhost: {
                required: true
            },
            dbuser: {
                required: true
            },
            dbpw: {
                required: true
            },
            dbname: {
                required: true
            },
            superadmin: {
                required: true
            },
            saemail: {
                required: true,
                email: true
            },
            sapw: {
                required: true
            }
        },
        submitHandler: function(form) {
            console.log(form);
            form.submit();
        }
    });*/

    $('#root_dir').change(function() {
        var dirpath = $('#root_dir').val();
        $.ajax({
            method: 'POST',
            url: 'ajax/instvalidate.php',
            data: {dirpath: dirpath}
        }).done(function(data){
            console.log(data);

            if(data == '1'){
                $('#valstatus').text('Root Path: ');
                $('#valstatus').append('Directory exists!').css('color', 'green');
            } else {
                $('#valstatus').text('Root Path: ');
                $('#valstatus').append('Directory does not exist').css('color', 'red');

            }
        })
    });
})
