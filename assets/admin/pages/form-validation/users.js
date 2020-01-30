$('#form_Save').on('click', function () {
    $('form').validate({
        submitHandler: function (form) {
            form.submit();
        }
    });
    
jQuery.validator.addMethod("validate_email", function(value, element) {

    if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
        return true;
    } else {
        return false;
    }
}, "Please enter a valid Email.");

    $("#form_customer_name").rules("add", {
        required: true,
        minlength: 4
    });
    $("#form_customer_phone").rules("add", {
        required: true,
        number: true,
        minlength: 10
    });
     $("#form_customer_email").rules("add", {
        required: true,
        validate_email: true
    });
       $("#form_customer_password").rules("add", {
        required: true,
        minlength: 4
    });
});


