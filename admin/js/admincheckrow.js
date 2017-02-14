$(document).ready(function() {
    $('#userlist tr').click(function(event) {
        if (event.target.type !== 'checkbox') {
            $(':checkbox', this).trigger('click');
        }
    });
    $("input[type='checkbox']").change(function (e) {
        if ($(this).is(":checked")) {

            $(this).closest('tr:not("#headrow")').addClass("checkedrow");

        } else {
            $(this).closest('tr').removeClass("checkedrow");
        }
    });
});

