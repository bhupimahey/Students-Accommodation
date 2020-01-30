$('#form_Save').on('click', function () {
    $('form').validate({
        submitHandler: function (form) {
            form.submit();
        }
    });


    $("#form_property_title").rules("add", {
        required: true,
        minlength: 4
    });

    $("#form_lease_start_date").rules("add", {
        required: true,
    }); 
    
   $("#form_property_tenant").rules("add", {
        required: true,
        minlength: 1,
         maxlength: 2,
         number:true
    });
    
     $("#form_property_address").rules("add", {
        required: true,
        minlength: 4
    });
    
});

$(".datepicker").datepicker({
    dateFormat: 'yy-mm-dd'
});
