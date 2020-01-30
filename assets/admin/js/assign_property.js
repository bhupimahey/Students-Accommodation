"use strict";

function checkAll(checkId){
        var inputs = document.getElementsByTagName("input");
        for (var i = 0; i < inputs.length; i++) { 
		    if (inputs[i].type == "checkbox" && inputs[i].name == checkId) { 
                if(inputs[i].checked == true) {
                    inputs[i].checked = false ;
                } else if (inputs[i].checked == false ) {
                    inputs[i].checked = true ;
                }
            }  
        }  
    }
$(document).ready(function() {	
    
       $('.usersrequestmodal .modal-footer #submitrequest').on('click', function(event) {
		  
		   var count_request_status = $("[name='request_status']:checked").length;
		   var remarks              = $("#resolution_given").val();
		   var error_message='';
		   var error=0;
		   if(count_request_status==0){
			 error=error+1;
			 error_message +='Choose Status <br>';
		    }
		   if(remarks==''){
			 error=error+1;
			 error_message +='Write Remarks <br>';
		    }	 
		if(error >0){	 
		 $(".usersrequestmodal").modal('hide');
		 $.confirm({
        	   	    columnClass: 'medium',
                    title: error_message,
                    content: '',
                    type: 'red',
                    typeAnimated: true,
                    });
     			   return false;	    
	       }
		  else{
			 $.ajax({
			  type: "POST",
			  url: "/roompanel/requests/change_status",
			  data: $("#requestfrm").serializeArray(),
			  success:function(result){		
			   window.location.href="requests";
			},		  
		});  
			  
		  }
		 return false;
	  });



	   $('.propertymodal .modal-footer #submitproperty').on('click', function(event) {
	        var count_propid_status = $("[name='propid']:checked").length;
		   var error_message='';
		   var error=0;
		   if(count_propid_status==0){
			 error=error+1;
			 error_message +='Choose Property <br>';
		    }
		if(error >0){	 
		 $(".propertymodal").modal('hide');
		 $.confirm({
        	   	    columnClass: 'medium',
                    title: error_message,
                    content: '',
                    type: 'red',
                    typeAnimated: true,
                    });
     			   return false;	    
	       }
		  else{
		      var property_info = $('input[name=propid]:checked').val();
		  var property_info_new = property_info.split("_");
		  var customer_id = property_info_new[0];
		  var property_id = property_info_new[1];
		   
		 $.ajax({
		  type: "POST",
		  url: "/roompanel/users/assign_propert",
		  data: {"customer_id":customer_id,"property_id":property_id},
		  success:function(result){		
			   window.location.href="users";
			},		  
		});
		      
		  }
		
		 
	  });
	  
	  $("#addcleaning").on("click",function(){
		 var count_checked = $("[name='cleaning_dates[]']:checked").length; // count the checked rows 
		 if(count_checked==0){
		 $.confirm({
        	   	    columnClass: 'medium',
                    title: 'Choose property to set cleaning date',
                    content: '',
                    type: 'red',
                    typeAnimated: true,
                    });
     			   return false;	    
	       }else{			
			$('#cleandate_modal').modal({
	   	      backdrop: 'static',
    		  keyboard: false
		    })
		  }
	  });
	  
	  
	  $("#sendmail_users").on("click",function(){
		 var count_checked = $("[name='user_ids[]']:checked").length; // count the checked rows 
		 if(count_checked==0){
		 $.confirm({
        	   	    columnClass: 'medium',
                    title: 'Choose users to send mail',
                    content: '',
                    type: 'red',
                    typeAnimated: true,
                    });
     			   return false;	    
	       }else{			
			$('#usersmail_modal').modal({
	   	      backdrop: 'static',
    		  keyboard: false
		    })
		  }
	  });
	  
	  
	  $("#sendmail_property_users").on("click",function(){
		  var count_checked = $("[name='cleaning_dates[]']:checked").length; // count the checked rows 
		  if(count_checked==0){
		  $.confirm({
        	   	    columnClass: 'medium',
                    title: 'Choose property to send mail to active tenant',
                    content: '',
                    type: 'red',
                    typeAnimated: true,
                    });
     			   return false;	    
	       }else{			
			$('#property_usersmail_modal').modal({
	   	      backdrop: 'static',
    		  keyboard: false
		    })
		  }
	  });
	  $('#property_usersmail_modal .modal-footer #sendproperty_usermail_btn').on('click', function(event) {
		  var property_mail_content = CKEDITOR.instances.property_mail_content.getData();
		  var property_mail_subject = $("#property_mail_subject").val();
		  var property_mail_cc = $("#property_mail_cc").val();
		  if(property_mail_content=='' && property_mail_subject==''){
			  $('#property_usersmail_modal').modal('hide');
			  $.confirm({
        	   	    columnClass: 'medium',
                    title: 'Subject & Mail content required !',
                    content: '',
                    type: 'red',
                    typeAnimated: true,
                    });
     			   return false;
		  }
		  else if(property_mail_cc!='' && ValidateEmail(property_mail_cc)==false){
			  $('#property_usersmail_modal').modal('hide');
			  $.confirm({
        	   	    columnClass: 'medium',
                    title: 'Please enter valid email !',
                    content: '',
                    type: 'red',
                    typeAnimated: true,
                    });
     			   return false;
		  }
		 else{
		     
		     
		      var UserIdsProp = [];
            $.each($("input[name='cleaning_dates[]']:checked"), function(){
                UserIdsProp.push($(this).val());
            });
            
		     var mail_attachment_fd = new FormData();
		   if($('#property_mail_attachment')[0].files[0]){
       	    var files = $('#property_mail_attachment')[0].files[0];
            mail_attachment_fd.append('file',files);
		   }
		   
        mail_attachment_fd.append("property_id", UserIdsProp);
        mail_attachment_fd.append("mailcontent", property_mail_content);
        mail_attachment_fd.append("mail_subject", property_mail_subject);
        mail_attachment_fd.append("mailcc", property_mail_cc);
        
        
          
		     
		   $('#property_usersmail_modal .modal-footer #sendproperty_usermail_btn').text('Please wait....');  
		   $('#property_usersmail_modal .modal-footer #sendproperty_usermail_btn').attr("disabled", true);
		   $('#property_usersmail_modal .modal-footer #sendproperty_usermail_cancel_btn').attr("disabled", true);
		   
		     
	       
		   $.ajax({
		     type: "POST",
		     url: "/roompanel/property/send_mail",
		      processData: false,
             contentType: false,
		     data: mail_attachment_fd,
		     success:function(result){	
		      if(result)
			  window.location.href="property";
			},		  
		});
		return false;
		 }
		
		 
	  });
	  
	  
	  $('#usersmail_modal .modal-footer #sendusermail_btn').on('click', function(event) {
		  var mail_content = CKEDITOR.instances.mail_content.getData();
		  var mail_subject = $("#mail_subject").val();
		  var user_mail_cc = $("#mail_cc").val();
		   
		  
		  
		
		  if(mail_content=='' && mail_subject==''){
			  $('#usersmail_modal').modal('hide');
			  $.confirm({
        	   	    columnClass: 'medium',
                    title: 'Subject & Mail content required !',
                    content: '',
                    type: 'red',
                    typeAnimated: true,
                    });
     			   return false;	  
			  
		  }
		  else if(user_mail_cc!='' && ValidateEmail(user_mail_cc)==false){
			  $('#usersmail_modal').modal('hide');
			  $.confirm({
        	   	    columnClass: 'medium',
                    title: 'Please enter valid email !',
                    content: '',
                    type: 'red',
                    typeAnimated: true,
                    });
     			   return false;
		  }
		 else{
		    
		   $('#usersmail_modal .modal-footer #sendusermail_btn').text('Please wait....');  
		    $('#usersmail_modal .modal-footer #sendusermail_btn').attr("disabled", true);
		   $('#usersmail_modal .modal-footer #sendusermail_cncl_btn').attr("disabled", true);
		   
		     
	        var UserIdsProp = [];
            $.each($("input[name='user_ids[]']:checked"), function(){
                UserIdsProp.push($(this).val());
            });
            
       var mail_attachment_fd = new FormData();
		   if($('#mail_attachment')[0].files[0]){
       	    var files = $('#mail_attachment')[0].files[0];
            mail_attachment_fd.append('file',files);
		   }
		   
        mail_attachment_fd.append("user_id", UserIdsProp);
        mail_attachment_fd.append("mailcontent", mail_content);
        mail_attachment_fd.append("mail_subject", mail_subject);
        mail_attachment_fd.append("user_mail_cc", user_mail_cc);


        
		   $.ajax({
		     type: "POST",
		     url: "users/send_mail",
		     processData: false,
             contentType: false,           
		     data:mail_attachment_fd,
		     success:function(result){	
		      if(result)
			 window.location.href="users";
			},		  
		});
		return false;
		 }
		
		 
	  });
	  
	  $('#cleandate_modal .modal-footer #save_dates').on('click', function(event) {
		    var clean_date = $("#clean_date").val();
		  
		  if(clean_date==''){
			  $('#cleandate_modal').modal('hide');
			$.confirm({
        	   	    columnClass: 'small',
                    title: 'Select cleaning date',
                    content: '',
                    type: 'red',
                    typeAnimated: true,
                    });
     			   return false;	  
			  
		  }
		 else{
	        var CleaningDatesProp = [];
            $.each($("input[name='cleaning_dates[]']:checked"), function(){
                CleaningDatesProp.push($(this).val());
            });
		 
		   var clean_notes = $("#clean_notes").val();
		   $.ajax({
		     type: "POST",
		     url: "property/setup_clean_date",
		     data: {"property_id":CleaningDatesProp,"cleandate":clean_date,"clean_notes":clean_notes},
		     success:function(result){		
			  window.location.href="property";
			},		  
		});
		
		 }
		
		 
	  });
	  
	  
	  $("#sendmail_tenants_users").on("click",function(){
		  var count_checked = $("[name='user_ids[]']:checked").length; // count the checked rows 
		  if(count_checked==0){
		  $.confirm({
        	   	    columnClass: 'medium',
                    title: 'Choose user to send mail',
                    content: '',
                    type: 'red',
                    typeAnimated: true,
                    });
     			   return false;	    
	       }else{			
			$('#tenants_usersmail_modal').modal({
	   	      backdrop: 'static',
    		  keyboard: false
		    })
		  }
	  });
$('#tenants_usersmail_modal .modal-footer #sendtenants_usermail_btn').on('click', function(event) {
	
		  var tenant_mail_content = CKEDITOR.instances.tenant_mail_content.getData();
		  var tenant_mail_subject = $("#tenant_mail_subject").val();
		   var tenant_user_mail_cc = $("#tenant_mail_cc").val();
		   
		  
		  if(tenant_mail_content=='' && tenant_mail_subject==''){
			  $('#tenants_usersmail_modal').modal('hide');
			  $.confirm({
        	   	    columnClass: 'medium',
                    title: 'Subject & Mail content required !',
                    content: '',
                    type: 'red',
                    typeAnimated: true,
                    });
     			   return false;	  
			  
		  }
		  else if(tenant_user_mail_cc!='' && ValidateEmail(tenant_user_mail_cc)==false){
			  $('#tenants_usersmail_modal').modal('hide');
			  $.confirm({
        	   	    columnClass: 'medium',
                    title: 'Please enter valid email !',
                    content: '',
                    type: 'red',
                    typeAnimated: true,
                    });
     			   return false;
		  }
		 else{
		    
		    $('#tenants_usersmail_modal .modal-footer #sendtenants_usermail_btn').text('Please wait....');  
		    $('#tenants_usersmail_modal .modal-footer #sendtenants_usermail_btn').attr("disabled", true);
		    $('#tenants_usersmail_modal .modal-footer #sendtenants_usermail_cncl_btn').attr("disabled", true);		   
		     
		   var UserIdsProp = [];
            $.each($("input[name='user_ids[]']:checked"), function(){
                UserIdsProp.push($(this).val());
            });  
		     var mail_attachment_fd = new FormData();
		   if($('#tenant_mail_attachment')[0].files[0]){
       	    var files = $('#tenant_mail_attachment')[0].files[0];
            mail_attachment_fd.append('file',files);
		   }
		   
        mail_attachment_fd.append("user_id", UserIdsProp);
        mail_attachment_fd.append("mailcontent", tenant_mail_content);
        mail_attachment_fd.append("mail_subject", tenant_mail_subject);
        mail_attachment_fd.append("mailcc", tenant_user_mail_cc);
        
		     
		     
	        
		   $.ajax({
		     type: "POST",
		     url: "/roompanel/reports/tenants/send_mail",
		     processData: false,
             contentType: false, 
		     data:mail_attachment_fd,
		     success:function(result){	
		        if(result)
			  window.location.href="/roompanel/reports/vacating_tenants";
			},		  
		});
		return false;
		 }
		
		 return false;
	  });	  

	  
});

function ValidateEmail(mail) 
{
 if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))
  {
    return (true)
  }
    return (false)
}
