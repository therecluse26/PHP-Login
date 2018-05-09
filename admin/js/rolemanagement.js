//User Management scripts
/* HANDLES POPOVERS FOR USER INFO */
function userInfoPull(id, elem) {
  $.ajax({
    type: "POST",
    url: "ajax/getuserinfo.php",
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
    url: "ajax/getroleusers.php",
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

      $('#users').multiselect({
          search: {
              left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
              right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
          },
          fireSearch: function(value) {
              return value.length > 3;
          }
      });
    },
    error: function(req, status, err){
      console.log(err);
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
      var name = $(this).text()
      new_users.push({"user_id": value, "user_name": name});
    });

    formData.push(toObject(new_users));
    formJson = JSON.stringify(formData);
    sendData.append('formData', formJson);
    sendData.append('roleId', id);
    sendData.append('csrf_token', $('meta[name="csrf_token"]').attr("value"));

    $.ajax({
      type: "POST",
      url: "ajax/updateroleusers.php",
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

          $('#usersModal').modal('toggle');

        } else {
          alert('Role must have at least one user!');
        }
      }
    });
  });
  /****************************/



/* DATATABLE INITIALIZATION */
$(document).ready(function() {
  usertable = $('#roleList').DataTable({
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
        { name: "required",
          searchable: false
        },
        { name: "default_role",
          searchable: false
        },
        {
          name: "users",
          searchable: false,
          render: function(data, type, row){
            return "<button id='usersbtn_"+row[0]+"' class='btn btn-info'>Assign Users</button>"
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
    ajax: "ajax/rolemanagementajax.php?csrf_token="+ $('meta[name="csrf_token"]').attr("value"),
    scrollY: "600px",
    scrollCollapse: true,
    lengthMenu: [[10, 25, -1], [10, 25, "All"]],
    select: {
      style:    "multi",
      selector: 'td:first-child'
    },
    buttons: [
        "selectAll",
        "selectNone"
    ]
  }).on("select", function(){
      //console.log("selected");
  });



});
/****************************/

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
