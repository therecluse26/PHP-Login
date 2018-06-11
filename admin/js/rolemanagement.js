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


/* HANDLES MODAL FOR ROLE USERS */
$('body').on('click', "button[id^='usersbtn_']", function(){
  var id = this.id.split('_')[1];
  roleUsersList(id);
});

function roleUsersList(id) {

  $.ajax({
    type: "POST",
    url: "ajax/role_getusers.php",
    data: { "role_id": id, "csrf_token": $('meta[name="csrf_token"]').attr("value") },
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
    success: function(user_array){
      user_array = JSON.parse(user_array);
      $('#usersButton').click();
      $('#users-selected').empty();
      $('#users-available').empty();
      $('#role_id').val(id);

      $.each(user_array['role_users'], function(key, value){
        $('#users-selected').append("<option value='"+value.id+"'>"+value.username+"</option>");
      });
      $.each(user_array['diff_users'], function(key, value){
        $('#users-available').append("<option value='"+value.id+"'>"+value.username+"</option>");
      })

      $("select[id^='users-']").multiselect({
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

$('#saveUsers').click(function(){

    var sendData = new FormData();
    var formData = [];
    var new_users = [];

    var id = $('#role_id').val();
    $('#users-selected > option').each(function(){
      var value = $(this).val();
      new_users.push($(this).val());
    });

    formJson = JSON.stringify(new_users);
    sendData.append('formData', formJson);
    sendData.append('roleId', id);
    sendData.append('csrf_token', $('meta[name="csrf_token"]').attr("value"));

    $.ajax({
      type: "POST",
      url: "ajax/role_updateusers.php",
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
        roleTable.ajax.reload();
        $('#usersModal').modal('toggle');
      },
      error: function (xhr, error, thrown) {
        console.log( error );
      }
    });
  });
  /****************************/



/* DATATABLE INITIALIZATION */
$(document).ready(function() {
  roleTable = $('#roleList').DataTable({
    dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    order: [[1, "asc"]],
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
        { name: "user count",
          width: "100px",
          searchable: false
        },
        {
          name: "assign users",
          width: "100px",
          searchable: false,
          render: function(data, type, row){
            return "<button id='usersbtn_"+row[0]+"' class='btn btn-info'>Assign Users</button>"
          }
        },
        {
          name: "edit role",
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
      url: "ajax/roles_getall.php?csrf_token="+ $('meta[name="csrf_token"]').attr("value"),
      error: function (xhr, error, thrown) {
        alert( xhr.responseJSON.Error );
      }
    },
    scrollY: "600px",
    scrollCollapse: true,
    lengthMenu: [[10, 25, -1], [10, 25, "All"]],
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
            roleTable.rows({ page: 'current'}).select();
            roleTable.rows({ search: 'removed'}).deselect();
          }
        },
        "selectNone",
        { text: 'Add New Role',
          action: function ( e, dt, node, config ) {
            $('#newRole').modal('show');
          },
          className: "btn-success"
        },
        { text: 'Delete Selected',
          action: function ( e, dt, node, config ) {
            var selected_array = dt.rows( { selected: true } ).data();
            if( confirm("Are you sure you want to delete the selected roles?") ){
              for (var i = 0, len = selected_array.length; i < len; i++) {
                if(selected_array[i][0] == 1 || selected_array[i][0] == 2 || selected_array[i][0] == 3){
                  alert("Cannot delete Admin or Standard User roles");
                  break;
                } else {
                  deleteRole(selected_array[i][0], 'usersbtn_'+selected_array[i][0]);
                }
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


function deleteRole(id, btn_id){
  var idJSON = "[" + JSON.stringify(id) + "]";
  $.ajax({
    type: "POST",
    url: "ajax/roles_delete.php",
    data: {"ids": idJSON, "csrf_token": $('meta[name="csrf_token"]').attr("value")},
    async: false,
    success: function(resp){
      roleTable.row( $('#'+btn_id).parents('tr') ).remove().draw();
    },
    error: function(err){
      alert(err.responseText);
    }
  });
}


$("#newRoleForm").submit(function(event){
    event.preventDefault();

    var roleName = $("#new_RoleName").val();
    var roleDescription = $("#new_RoleDescription").val();

    $.ajax({
      url: "ajax/roles_add.php",
      type: "POST",
      data: { "roleName": roleName, "roleDescription": roleDescription, "csrf_token": $('meta[name="csrf_token"]').attr("value") },
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
          roleTable.ajax.reload();
          $("#newRole").modal('hide');
          $("#newRoleForm")[0].reset();
      },
      error: function(err){
          console.log(err);
      }
    });
  });

  $("#editRoleForm").submit(function(event){
      event.preventDefault();

      var roleId = $("#edit_role_id").val();
      var roleName = $("#edit_RoleName").val();
      var roleDescription = $("#edit_RoleDescription").val();

      $.ajax({
        url: "ajax/role_update.php",
        type: "POST",
        data: { "roleId": roleId, "roleName": roleName, "roleDescription": roleDescription, "csrf_token": $('meta[name="csrf_token"]').attr("value") },
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
            roleTable.ajax.reload();
            $("#editRole").modal('hide');
            $("#editRoleForm")[0].reset();
        },
        error: function(err){
            console.log(err);
        }
      });
    });


function editRole(id){

  var id = id;

  $.ajax({
    type: "POST",
    url: "ajax/role_getdata.php",
    data: { "role_id": id, "csrf_token": $('meta[name="csrf_token"]').attr("value") },
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

      $("#editRoleForm").trigger("reset");

      $("#edit_role_id").val(respdata.id);
      $("#edit_RoleName").val(respdata.name);
      $("#edit_RoleDescription").val(respdata.description);
      $("#edit_RoleCategory").val(respdata.category);

      $('#editRole').modal('show');

    },
    error: function (xhr, error, thrown) {
      console.log( thrown );
    }
  });
}

//Role assignment box button logic
(function () {
    $('#btnRight').click(function (e) {
        var selectedOpts = $('#users-available option:selected');
        if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
        }
        $('#users-selected').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    });
    $('#btnAllRight').click(function (e) {
        var selectedOpts = $('#users-available option');
        if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
        }
        $('#users-selected').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    });
    $('#btnLeft').click(function (e) {
        var selectedOpts = $('#users-selected option:selected');
        if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
        }
        $('#users-available').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    });
    $('#btnAllLeft').click(function (e) {
        var selectedOpts = $('#users-selected option');
        if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
        }
        $('#users-available').append($(selectedOpts).clone());
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
