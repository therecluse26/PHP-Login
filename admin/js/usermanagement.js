//User Management scripts
/* HANDLES POPOVERS FOR USER INFO */
function userInfoPull(id, elem) {
  $.ajax({
    type: "POST",
    url: "ajax/user_getinfo.php",
    data: { "user_id": id, "csrf_token": $('meta[name="csrf_token"]').attr("value") },
    async: false,
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


/* HANDLES MODAL FOR USER ROLES */
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

function userRolesList(id) {

  var return_arr = null;

  $.ajax({
    type: "POST",
    url: "ajax/user_getroles.php",
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
    success: function(role_array){
      return_arr = role_array;
    },
    error: function (xhr, error, thrown) {
      console.log( error );
    }
  });
  return return_arr;

};

$('#saveRoles').click(function(){

    var sendData = new FormData();
    var formData = [];
    var new_roles = [];

    var id = $('#user_id').val();
    $('#roles-selected > option').each(function(){
      var value = $(this).val();
      var name = $(this).text()
      new_roles.push({"role_id": value, "role_name": name});
    });

    formData.push(toObject(new_roles));
    formJson = JSON.stringify(formData);
    sendData.append('formData', formJson);
    sendData.append('userId', id);
    sendData.append('csrf_token', $('meta[name="csrf_token"]').attr("value"));

    $.ajax({
      type: "POST",
      url: "ajax/user_updateroles.php",
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

        if (response != 'false'){

          response = JSON.parse(response);

          $('#rolesModal').modal('toggle');
          $("#rolestd_" + id).empty();

          $.each(response[0], function(key, value){
            $('#rolestd_' + id).append('<a href="#" id="roles_'+id+'">'+value.role_name+'</a><br>');
            usertable.row( $('#rolestd_' + id).parents('tr')  ).draw("full-hold");
            //usertable.draw("full-hold");
          });

        } else {
          alert('User must have at least one role!');
        }
      },
      error: function (xhr, error, thrown) {
        console.log( error );
      }
    });
  });
  /****************************/



function banUser(id, btn_id, ban_hours, ban_reason){

  if (typeof ban_hours === 'undefined') {
    var ban_hours = Number(window.prompt("How long (in hours) for this ban?"));
  }
  if (typeof ban_reason === 'undefined') {
    var ban_reason = window.prompt("What is the reason for this ban?");
  }

  var uidJSON = "[" + JSON.stringify(id) + "]";

  if (ban_hours !== 0){
    $.ajax({
      type: "POST",
      url: "ajax/users_ban.php",
      data: { "uid": uidJSON, "ban_hours": ban_hours, "ban_reason": ban_reason,
              "csrf_token": $('meta[name="csrf_token"]').attr("value")},
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
      success: function(resp){

        usertable.row( $('#'+btn_id).parents('tr') ).remove().draw();

      },
      error: function (error) {
        alert( error.responseText );
      }
    });
  }

}

/* DATATABLE INITIALIZATION */
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
          searchable: true,
          render: function(data, type, row){
            return "<a role='button' data-container='body' data-html='true' data-toggle='popover' data-trigger='focus' class='btn btn-primary' id='info_"+row[0]+"'>"+data+"</a>";
          }
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
    ajax: {
      url:"ajax/users_getallactive.php?csrf_token="+ $('meta[name="csrf_token"]').attr("value"),
      error: function (xhr, error, thrown) {
        alert( xhr.responseJSON.Error );
      }
    },
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
        { text: 'Ban Selected',
          action: function ( e, dt, node, config ) {
            var selected_array = dt.rows( { selected: true } ).data();
            if (selected_array.length > 0) {

              var ban_hours = Number(window.prompt("How long (in hours) for this ban?"));
              var ban_reason = window.prompt("What is the reason for this ban?");
              if (ban_hours !== 0){
                for (var i = 0, len = selected_array.length; i < len; i++) {
                  banUser(selected_array[i][0], 'banbtn_'+selected_array[i][0], ban_hours, ban_reason);
                }
              }

            }
          },
          className: "btn-warning"
        },
        { text: 'Delete Selected',
          action: function ( e, dt, node, config ) {

            var selected_array = dt.rows( { selected: true } ).data();

            if( confirm("Are you sure you want to delete the selected user(s)?") ){

              for (var i = 0, len = selected_array.length; i < len; i++) {

                deleteUser(selected_array[i][0], 'info_'+selected_array[i][0]);

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


function deleteUser(id, btn_id){
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
      alert(err.responseText);
    }
  });
}


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
