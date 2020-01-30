$('#form_Save').on('click', function () {
    $('form').validate({
        submitHandler: function (form) {
            form.submit();
        }
    });


    $("#form_event_title").rules("add", {
        required: true,
        minlength: 4
    });
    
    $("#form_event_venue").rules("add", {
        required: true,
        minlength: 4
    });
  $("#form_event_datetime").rules("add", {
        required: true,
    });
  $("#form_event_desc").rules("add", {
        required: true,
          minlength: 4
    });   
});



 $('.form_datetime').datetimepicker();