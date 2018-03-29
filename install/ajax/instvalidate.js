$(document).ready(function(){

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
