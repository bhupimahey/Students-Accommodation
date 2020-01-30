var pagerTable=$('#products_table').DataTable({
         "bProcessing": true,
         "serverSide": true,
		 "bStateSave": true,
		 dom: 'Blfrtip',
		 buttons: [],		
	     "ajax":{
            url :admin_path+"albums/ajax_products_view", // json datasource
            type: "post",  // type of method  , by default would be get
			"data": function ( send_data ) {
               
            },
            error: function(){  // error handling code              
            }
          }
        }); 

