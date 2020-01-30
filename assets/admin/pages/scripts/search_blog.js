var pagerTable=$('#blog_table').DataTable({
         "bProcessing": true,
         "serverSide": true,
		 "bStateSave": true,
		 dom: 'Blfrtip',
		 buttons: [],		
	     "ajax":{
            url :admin_path+"blog/ajax_blog_view", // json datasource
            type: "post",  // type of method  , by default would be get
			"data": function ( send_data ) {
               
            },
            error: function(){  // error handling code              
            }
          }
        }); 

