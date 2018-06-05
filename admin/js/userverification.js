//User Management scripts

/* DATATABLE INITIALIZATION */
$(document).ready(function() {
  usertable = $('#userlist').DataTable({
    dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    order: [[3, "desc"]],
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
          searchable: true,
          render: function(data, type, row){
            return data;
          }
        },
        { name: "email",
          searchable: true
        },
        { name: "timestamp",
          searchable: false,
          render: function(data, type, row){
            return data + "<button id='delbtn_"+row[0]+
                          "' onClick='deleteUnverifiedUser(&apos;"+row[0]+"&apos;, &apos;delbtn_"+row[0]+
                          "&apos;)' class='btn btn-danger pull-right'>Delete</button>\
                          <button id='verbtn_"+row[0]+
                          "' onClick='verifyUser(&apos;"+row[0]+"&apos;, &apos;verbtn_"+row[0]+
                          "&apos;)' class='btn btn-success pull-right'>Verify</button>"

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
    ajax: "ajax/users_getunverified.php?csrf_token="+ $('meta[name="csrf_token"]').attr("value"),
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
        { text: 'Verify Selected',
          action: function ( e, dt, node, config ) {
            var selected_array = dt.rows( { selected: true } ).data();
            for (var i = 0, len = selected_array.length; i < len; i++) {
              verifyUser(selected_array[i][0], 'verbtn_'+selected_array[i][0]);
            }
          },
          className: "btn-success"
        },
        { text: 'Delete Selected',
          action: function ( e, dt, node, config ) {
            
            var selected_array = dt.rows( { selected: true } ).data();

            for (var i = 0, len = selected_array.length; i < len; i++) {

              deleteUnverifiedUser(selected_array[i][0], 'delbtn_'+selected_array[i][0]);

            }
          },
          className: "btn-danger"
        }
    ]
  }).on("select", function(){
      //console.log("selected");
  });
});
/****************************/


function verifyUser(id, btn_id){
  var uidJSON = "[" + JSON.stringify(id) + "]";
  $.ajax({
    type: "POST",
    url: "ajax/user_verify.php",
    data: {"uid": uidJSON, "csrf_token": $('meta[name="csrf_token"]').attr("value")},
    async: false,
    success: function(resp){
      usertable.row( $('#'+btn_id).parents('tr') ).remove().draw();
    }
  });
}
function deleteUnverifiedUser(id, btn_id){
  var idJSON = "[" + JSON.stringify(id) + "]";
  $.ajax({
    type: "POST",
    url: "ajax/users_delete.php",
    data: {"ids": idJSON, "csrf_token": $('meta[name="csrf_token"]').attr("value")},
    async: false,
    success: function(resp){
      usertable.row( $('#'+btn_id).parents('tr') ).remove().draw();
    },
    error: function(err){
      console.log(err);
    }
  });
}

// Converts array to object
function toObject(arr) {
  var rv = {};
  for (var i = 0; i < arr.length; ++i)
    rv[i] = arr[i];
  return rv;
}
