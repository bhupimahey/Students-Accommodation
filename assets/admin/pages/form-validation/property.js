$('#form_Save').on('click', function () {
    var proptenants = $('#aoccupants').val();
	var isproperty_status =$("input[name='form[property_status]']:checked").val();
    if(proptenants>0 && isproperty_status==0){
  
         $.confirm({
        	   	    columnClass: 'medium',
                    title: 'Property could not be deactivated(active tenants present)',
                    content: '',
                    type: 'red',
                    typeAnimated: true,
                    });
                    
                    return false;
        
    }
    else
    return true;
    
    $('form').validate({
        submitHandler: function (form) {
            form.submit();
        }
    });

    $("#form_property_title").rules("add", {
        required: true,
        minlength: 4
    });

    $("#form_property_tenant").rules("add", {
        required: true,
        minlength: 1,
         maxlength: 2,
         number:true
    });
    $("#form_lease_start_date").rules("add", {
        required: true,
    }); 
    
     $("#form_property_address").rules("add", {
        required: true,
        minlength: 4
    });
    
});

$(".datepicker").datepicker({
    dateFormat: 'yy-mm-dd'
});


$( document ).ready(function() {

 var rooomscounter=10;
 $(document).on("click","#addmore_rooms", function (e) {	
 rooomscounter=rooomscounter+1;

   var room_typeoptions= $("#form_room_type > option").clone();
   var room_furnoptions= $("#form_room_furnishings > option").clone();
   var room_bathoptions= $("#form_room_bathroom > option").clone();
   
  var tablerow= '<div class="form-group row" id="mainrow'+rooomscounter+'"> <div class="col-sm-3"> <div class="col-sm-12"> <select name="prop_room_type[]" id="room_type'+rooomscounter+'" class="form-control"></select> <span class="messages"></span> </div></div><div class="col-sm-3"> <div class="col-sm-12"> <select name="prop_room_furnishings[]" id="room_furnishings'+rooomscounter+'" class="form-control"></select> <span class="messages"></span> </div></div><div class="col-sm-2"> <div class="col-sm-12"> <select name="prop_room_bathroom[]" id="room_bathroom'+rooomscounter+'" class="form-control"></select> <span class="messages"></span> </div></div><div class="col-sm-2"> <div class="col-sm-12"> <input type="text" name="prop_room_rent[]" class="form-control" id="room_rent'+rooomscounter+'" required="true"> <span class="messages"></span> </div></div><div class="col-sm-2"> <div class="col-sm-12"> <button type="button"  value="Add Room" class="btn btn-primary btn-sm remove_room" data-id="'+rooomscounter+'">Remove Room</button> </div></div></div>';
  $( ".roominsertdiv" ).before(tablerow);
  $('#room_type'+rooomscounter+'').append(room_typeoptions);
  $('#room_furnishings'+rooomscounter+'').append(room_furnoptions);
  $('#room_bathroom'+rooomscounter+'').append(room_bathoptions);  
 }); 
 
 $(document).on("click",".remove_room", function (e) {
   var roomid = $(this).attr("data-id");
	$("#mainrow"+roomid+"").remove();
});
  
});
