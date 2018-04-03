$(document).ready(function (e) {

var croppedimg;
var imgChange = false;

function ajaxSend(formData){

  formData.append("csrf_token", $('meta[name="csrf_token"]').attr("value"));

    $.ajax({
        url: "ajax/profileupdate.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (html) {

          if (html == 1) {
            $("#message").fadeOut(0, function (){
                $(this).html("<div class=\"alert alert-success alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Changes saved!</div>").fadeIn();
            })
            $('#submit').hide();
          }
          else {
            $("#message").fadeOut(0, function (){
                $(this).html(html).fadeIn();
            })
            $('#submit').show();

            $.ajax({
              url: "ajax/getimage.php",
              type: "POST",
              data: {"csrf_token": $('meta[name="csrf_token"]').attr("value")},
              success: function (img) {

                croppedimg.croppie('destroy');

                $("#imgholder").html("<img src='" + img + "?i="+ new Date().getTime() +"' class='img-thumbnail' id='imgthumb'></img>");

              }
            })
          }
        },
        beforeSend: function () {
            $("#message").fadeOut(0, function (){
                $(this).html("<p class='text-center'><img src='../login/images/ajax-loader.gif'></p>").fadeIn();
            })
        }
    });
}

function croppiegen(e){

    imgChange = true;


    $("<img />", {
        "src": e.target.result,
        "id": "imgthumb"
    }).appendTo($("#imgholder"));

    //Creates image cropper
    var imgcrop = $("#imgthumb").croppie({
        viewport: {
            width: 200,
            height: 200,
            type: 'square'
        },
        boundary: {
            width: 300,
            height: 300
        }
    });
    $("#imgholder").addClass('image-thumbnail');
    imgcrop.croppie('bind', {
        url: e.target.result
    });
    return imgcrop;
}

//Image preview/cropper
$("#userimage").on('change', function () {

        if (typeof (FileReader) != "undefined") {

            var image_holder = $("#imgholder");
            image_holder.empty();

            var reader = new FileReader();
            reader.onload = function (e) {

                croppedimg = croppiegen(e);

            }

            image_holder.show();
            reader.readAsDataURL($(this)[0].files[0]);
        }
    });


  //Ajax form submit
 $("#profileForm").submit(function ( e ) {

    e.preventDefault();
    var formData = new FormData(this);

    if(imgChange == true){

        croppedimg.croppie('result', {
        type: 'base64',
        format: 'jpeg',
        quality: '0.85',
        size: {
            width: 500,
            height: 500
            }
        }).then(function(userimg){
            formData.append('userimage', userimg);
            ajaxSend(formData);

        });
    } else {
        ajaxSend(formData);
    }

    return false;

  });
});
