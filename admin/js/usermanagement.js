$('body').on('click', "a[id^='roles_']", function(){
  $('#rolesButton').click();
  var id = this.id.split('_')[1];
  var role_array = JSON.parse(userRolesList(id));

  $('#roles-selected').empty();
  $('#roles-available').empty();
  $('#user_id').val(id);
  $.each(role_array['user_roles'], function(key, value){
    $('#roles-selected').append("<option value='"+value.id+"'>"+value.name+"</option>");
  });
  $.each(role_array['diff_roles'], function(key, value){
    $('#roles-available').append("<option value='"+value.id+"'>"+value.name+"</option>");
  })
});

function toObject(arr) {
  var rv = {};
  for (var i = 0; i < arr.length; ++i)
    rv[i] = arr[i];
  return rv;
}

function userRolesList(id) {

  var return_val = null;

  $.ajax({
    type: "POST",
    url: "ajax/getuserroles.php",
    data: { "user_id": id },
    async: false,
    success: function(role_array){

      return_val = role_array;

    }
  });
  return return_val;

}

function banUser(id, btn_id, ban_hours, ban_reason){

  if (typeof ban_hours === 'undefined') {
    var ban_hours = Number(window.prompt("How long (in hours) for this ban?"));
  }
  if (typeof ban_reason === 'undefined') {
    var ban_reason = window.prompt("What is the reason for this ban?");
  }

  var uidJSON = "[" + JSON.stringify(id) + "]";

  $.ajax({
    type: "POST",
    url: "ajax/banuserajax.php",
    data: { "uid": uidJSON, "ban_hours": ban_hours, "ban_reason": ban_reason },
    async: false,
    success: function(resp){

      usertable.row( $('#'+btn_id).parents('tr') ).remove().draw();

    }
  });

}

//Initialize DataTable
$(document).ready(function() {
  usertable = $('#userlist').DataTable({
    dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    order: [[4, "desc"]],
    columns: [
        { name: "id",
          visible: true,
          searchable: false,
          sortable: false,
          render: function(data, type, row){
            return "";
          }
        },
        { name: "username",
          searchable: true
        },
        { name: "email",
          searchable: true
        },
        { name: "roles",
          searchable: false,
          render: function(data, type, row){
            return "<a id='roles_"+row[0]+"'>"+data+"</a>";
          }
        },
        { name: "timestamp",
          searchable: false,
          render: function(data, type, row){
            return data + "<button id='banbtn_"+row[0]+"' onClick='banUser(&apos;"+row[0]+"&apos;, &apos;banbtn_"+row[0]+"&apos;)' class='btn btn-warning pull-right'>Ban</button>"
          }
        }
    ],
    columnDefs: [ {
        className: 'select-checkbox',
        targets: 0
    } ],
    processing: true,
    paging: true,
    serverSide: true,
    ajax: "ajax/usermanagementajax.php",
    scrollY: "600px",
    scrollCollapse: true,
    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
    select: {
      style:    "multi",
      selector: 'td:first-child'
    },
    buttons: [
        "selectAll",
        "selectNone",
        {
            text: 'Ban Selected',
            action: function ( e, dt, node, config ) {

              var ban_hours = Number(window.prompt("How long (in hours) for this ban?"));
              var ban_reason = window.prompt("What is the reason for this ban?");
              var selected_array = dt.rows( { selected: true } ).data();

              for (var i = 0, len = selected_array.length; i < len; i++) {
                banUser(selected_array[i][0], 'banbtn_'+selected_array[i][0], ban_hours, ban_reason);
              }
            }
        }
    ]
  }).on("select", function(){
      //console.log("selected");
  });
});


//Role assignment box button logic
(function () {
    $('#btnRight').click(function (e) {
        var selectedOpts = $('#roles-available option:selected');
        if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
        }
        $('#roles-selected').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    });
    $('#btnAllRight').click(function (e) {
        var selectedOpts = $('#roles-available option');
        if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
        }
        $('#roles-selected').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    });
    $('#btnLeft').click(function (e) {
        var selectedOpts = $('#roles-selected option:selected');
        if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
        }
        $('#roles-available').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    });
    $('#btnAllLeft').click(function (e) {
        var selectedOpts = $('#roles-selected option');
        if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
        }
        $('#roles-available').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    });
}(jQuery));
