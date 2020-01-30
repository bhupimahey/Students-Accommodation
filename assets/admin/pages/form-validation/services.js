$('#form_Save').on('click', function () {
    $('form').validate({
        submitHandler: function (form) {
            form.submit();
        }
    });


    $("#form_service_title").rules("add", {
        required: true,
        minlength: 4
    });
});


