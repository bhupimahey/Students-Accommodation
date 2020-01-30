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
		    
		   // $('#tenants_usersmail_modal .modal-footer #sendtenants_usermail_btn').text('Please wait....');  
		   // $('#tenants_usersmail_modal .modal-footer #sendtenants_usermail_btn').attr("disabled", true);
		   // $('#tenants_usersmail_modal .modal-footer #sendtenants_usermail_cncl_btn').attr("disabled", true);		   
		     
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
		     url: "reports/tenants/send_mail",
		     processData: false,
             contentType: false, 
		     data:mail_attachment_fd,
		     success:function(result){	
		        // if(result)
			  //window.location.href="reports/vacating_tenants";
			},		  
		});
		return false;
		 }
		
		 return false;
	  });	  
	  
