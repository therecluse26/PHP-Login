//User Management scripts
/* HANDLES POPOVERS FOR USER INFO */
function userInfoPull(id, elem) {
  $.ajax({
    type: "POST",
    url: "ajax/user_getinfo.php",
    data: { "user_id": id, "csrf_token": $('meta[name="csrf_token"]').attr("value") },
    async: false,
    beforeSend: function(){
      $.LoadingOverlay('show', {
        image: '../login/images/Spin-0.8s-200px.svg',
        imageAnimation: false,
        imageColor: '#428bca',
        fade: [200, 100]
      });
    },
    complete: function(){
      $.LoadingOverlay("hide");
    },
    success: function(user_info){
      user_info = JSON.parse(user_info);
      var user_info_html = '';
      for(var prop in user_info){
        if (user_info[prop] != '' && user_info[prop] != null){
          if(prop == 'UserImage'){
            user_info_html += '<br><div class="img-thumbnail"><img src="'+user_info[prop]+'" height="240px"></div>';
          } else {
            user_info_html += '<div><b>' + prop.replace(/([A-Z])/g, ' $1') + ': </b>'+ user_info[prop] +'</div>';
          }
        }
      }
      $(elem).attr('data-content', user_info_html).popover('show', {"html": true});
    },
    error: function (xhr, error, thrown) {
      console.log( error );
    }
  });
};

$('body').on('mouseover', "a[id^='info_']", function(){
  if( $(this).attr('data-content') ){
    $(this).popover('show', {"html": true});
  } else {
    var id = this.id.split('_')[1];
    userInfoPull(id, this);
  }
});
$('body').on('mouseleave', "a[id^='info_']", function(){
  $(this).popover('hide');
});
/****************************/


/* HANDLES MODAL FOR PERMISSION ROLES */
$('body').on('click', "button[id^='rolesbtn_']", function(){
  var id = this.id.split('_')[1];
  permissionRolesList(id);
});



function editPermission(id){

  var id = id;

  $.ajax({
    type: "POST",
    url: "ajax/permission_getdata.php",
    data: { "permission_id": id, "csrf_token": $('meta[name="csrf_token"]').attr("value") },
    beforeSend: function(){
      $.LoadingOverlay('show', {
        image: '../login/images/Spin-0.8s-200px.svg',
        imageAnimation: false,
        imageColor: '#428bca',
        fade: [200, 100]
      });
    },
    complete: function(){
      $.LoadingOverlay("hide");
    },
    success: function(response){
      var respdata = JSON.parse(response);
      $("#editPermissionForm").trigger("reset");
      $("#edit_permission_id").val(respdata.id);
      $("#edit_PermissionName").val(respdata.name);
      $("#edit_PermissionDescription").val(respdata.description);
      $("#edit_PermissionCategory").val(respdata.category);
      $('#editPermission').modal('show');
    },
    error: function (xhr, error, thrown) {
      console.log( error );
    }
  });
}

function permissionRolesList(id) {

  $.ajax({
    type: "POST",
    url: "ajax/permission_getroles.php",
    data: { "permission_id": id, "csrf_token": $('meta[name="csrf_token"]').attr("value") },
    beforeSend: function(){
      $.LoadingOverlay('show', {
        image: '../login/images/Spin-0.8s-200px.svg',
        imageAnimation: false,
        imageColor: '#428bca',
        fade: [200, 100]
      });
    },
    complete: function(){
      $.LoadingOverlay("hide");
    },
    success: function(role_array){
      role_array = JSON.parse(role_array);
      $('#permissionsButton').click();
      $('#roles-selected').empty();
      $('#roles-available').empty();
      $('#permission_id').val(id);

      $.each(role_array['permission_roles'], function(key, value){
        $('#roles-selected').append("<option value='"+value.id+"'>"+value.name+"</option>");
      });
      $.each(role_array['diff_roles'], function(key, value){
        $('#roles-available').append("<option value='"+value.id+"'>"+value.name+"</option>");
      })

      $("select[id^='roles-']").multiselect({
          search: {
              left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
              right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
          },
          fireSearch: function(value) {
              return value.length > 3;
          }
      });
    },
    error: function (xhr, error, thrown) {
      console.log( error );
    }
  });
};

$('#saveRoles').click(function(){

    var sendData = new FormData();
    var formData = [];
    var new_roles = [];

    var id = $('#permission_id').val();

    $('#roles-selected > option').each(function(){
      new_roles.push($(this).val());
    });

    formJson = JSON.stringify(new_roles);
    sendData.append('formData', formJson);
    sendData.append('permissionId', id);
    sendData.append('csrf_token', $('meta[name="csrf_token"]').attr("value"));

    $.ajax({
      type: "POST",
      url: "ajax/permission_updateroles.php",
      processData: false,
      contentType: false,
      data: sendData,
      beforeSend: function(){
        $.LoadingOverlay('show', {
          image: '../login/images/Spin-0.8s-200px.svg',
          imageAnimation: false,
          imageColor: '#428bca',
          fade: [200, 100]
        });
      },
      complete: function(){
        $.LoadingOverlay("hide");
      },
      success: function(response){
          response = JSON.parse(response);
          permissionTable.ajax.reload();
          $('#permissionsModal').modal('toggle');
      },
      error: function (xhr, error, thrown) {
        console.log( error );
      }
    });
  });
  /****************************/



/* DATATABLE INITIALIZATION */
$(document).ready(function() {
  permissionTable = $('#permissionList').DataTable({
    dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    order: [[3, "asc"],[1, "asc"]],
    columns: [
        { name: "id",
          visible: true,
          searchable: false,
          sortable: false,
          render: function(data, type, row){
            return "";
          }
        },
        { name: "name",
          searchable: true
        },
        { name: "description",
          searchable: true
        },
        { name: "category",
          searchable: true
        },
        {
          name: "assign roles",
          width: "90px",
          searchable: false,
          render: function(data, type, row){
            return "<button id='rolesbtn_"+row[0]+"' class='btn btn-info'>Assign roles</button>"
          }
        },
        {
          name: "edit permission",
          width: "40px",
          searchable: false
        }
    ],
    columnDefs: [ {
        className: 'select-checkbox',
        targets: 0
    } ],
    paging: true,
    ajax: {
      url: "ajax/permissions_getall.php?csrf_token="+ $('meta[name="csrf_token"]').attr("value"),
      error: function (xhr, error, thrown) {
        alert( xhr.responseJSON.Error );
      }
    },
    scrollY: "600px",
    scrollCollapse: true,
    lengthMenu: [[15, 30, -1], [15, 30, "All"]],
    select: {
      style:    "multi",
      selector: 'td:first-child'
    },
    buttons: [
        {
          extend: 'selectAll',
          className: 'selectall',
          action : function(e) {
            e.preventDefault();
            permissionTable.rows({ page: 'current'}).select();
            permissionTable.rows({ search: 'removed'}).deselect();
          }
        },
        "selectNone",
        { text: 'Add New Permission',
          action: function ( e, dt, node, config ) {
            $('#newPermission').modal('show');
          },
          className: "btn-success"
        },
        { text: 'Delete Selected',
          action: function ( e, dt, node, config ) {
            var selected_array = dt.rows( { selected: true } ).data();
            if( confirm("Are you sure you want to delete the selected permissions?") ){
              for (var i = 0, len = selected_array.length; i < len; i++) {

                deletePermission(selected_array[i][0], 'rolesbtn_'+selected_array[i][0]);
              }
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


function deletePermission(id, btn_id){
  var idJSON = "[" + JSON.stringify(id) + "]";
  $.ajax({
    type: "POST",
    url: "ajax/permissions_delete.php",
    data: {"ids": idJSON, "csrf_token": $('meta[name="csrf_token"]').attr("value")},
    async: false,
    success: function(resp){
      permissionTable.row( $('#'+btn_id).parents('tr') ).remove().draw();
    },
    error: function(err){
      console.log(err);
      alert(err.responseText);
    }
  });
}


$("#newPermissionForm").submit(function(event){
    event.preventDefault();

    var permissionName = $("#new_PermissionName").val();
    var permissionDescription = $("#new_PermissionDescription").val();
    var permissionCategory = $("#new_PermissionCategory").val();

    $.ajax({
      url: "ajax/permissions_add.php",
      type: "POST",
      data: { "permissionName": permissionName, "permissionDescription": permissionDescription, "permissionCategory": permissionCategory, "csrf_token": $('meta[name="csrf_token"]').attr("value") },
      beforeSend: function(){
        $.LoadingOverlay('show', {
          image: '../login/images/Spin-0.8s-200px.svg',
          imageAnimation: false,
          imageColor: '#428bca',
          fade: [200, 100]
        });
      },
      complete: function(resp){
        $.LoadingOverlay("hide");
      },
      success: function(response){
          permissionTable.ajax.reload();
          $("#newPermission").modal('hide');
          $("#newPermissionForm")[0].reset();
      },
      error: function(err){
          console.log(err);
      }
    });
  });

  $("#editPermissionForm").submit(function(event){
    event.preventDefault();

    var permissionId = $("#edit_permission_id").val();
    var permissionName = $("#edit_PermissionName").val();
    var permissionDescription = $("#edit_PermissionDescription").val();
    var permissionCategory = $("#edit_PermissionCategory").val();

    $.ajax({
      url: "ajax/permission_update.php",
      type: "POST",
      data: { "permissionId": permissionId, "permissionName": permissionName, "permissionDescription": permissionDescription, "permissionCategory": permissionCategory, "csrf_token": $('meta[name="csrf_token"]').attr("value") },
      beforeSend: function(){
        $.LoadingOverlay('show', {
          image: '../login/images/Spin-0.8s-200px.svg',
          imageAnimation: false,
          imageColor: '#428bca',
          fade: [200, 100]
        });
      },
      complete: function(resp){
        $.LoadingOverlay("hide");
      },
      success: function(response){
          permissionTable.ajax.reload();
          $("#editPermission").modal('hide');
          $("#editPermissionForm")[0].reset();
      },
      error: function(err){
          console.log(err);
      }
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

// Converts array to object
function toObject(arr) {
  var rv = {};
  for (var i = 0; i < arr.length; ++i)
    rv[i] = arr[i];
  return rv;
}
