/* DATATABLE INITIALIZATION */
$(document).ready(function() {
  usertable = $('#mailList').DataTable({
    dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    order: [[5, "desc"]],
    columns: [
        { name: "id",
          visible: true,
          searchable: false,
          sortable: false,
          render: function(data, type, row){
            return "";
          }
        },
        { name: "type",
          searchable: true
        },
        { name: "status",
          searchable: true
        },
        { name: "recipient",
          searchable: true
        },
        { name: "response",
          searchable: false
        },
        { name: "timestamp",
          searchable: false,
          render: function(data, type, row){
            return data + "<button id='delbtn_"+row[0]+
                          "' onClick='deleteLog(&apos;"+row[0]+"&apos;, &apos;delbtn_"+row[0]+
                          "&apos;)' class='btn btn-danger pull-right'>Delete</button>"
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
    ajax: "ajax/email_getunreadlogs.php?csrf_token="+ $('meta[name="csrf_token"]').attr("value"),
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
        { text: 'Delete Selected',
          action: function ( e, dt, node, config ) {
            var selected_array = dt.rows( { selected: true } ).data();
            for (var i = 0, len = selected_array.length; i < len; i++) {
              deleteLog(selected_array[i][0], 'delbtn_'+selected_array[i][0]);
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

function deleteLog(id, btn_id){
  //var logidJSON = "[" + JSON.stringify(id) + "]";
  $.ajax({
    type: "POST",
    url: "ajax/email_deletelogs.php",
    data: {"logid": id, "csrf_token": $('meta[name="csrf_token"]').attr("value")},
    async: false,
    success: function(resp){
      usertable.row( $('#'+btn_id).parents('tr') ).remove().draw();
    }
  });
}
