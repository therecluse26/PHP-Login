$("#selectAll").change(function () {
    $("input:checkbox").prop("checked", $(this).prop("checked"));
    if ($("input:checkbox").is(":checked")) {
        $("input:checkbox").closest("tr").addClass("checkedrow");
    } else {
        $("input:checkbox").closest("tr").removeClass("checkedrow");
    }
});
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
