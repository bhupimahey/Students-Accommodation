$(function() {
    // Setup html5 version
    $("#uploader").pluploadQueue({
        // General settings
        runtimes : 'html5,flash,silverlight,html4',
        url : "/roompanel/property/upload_images",
         
        chunk_size : '1mb',
        rename : true,
        dragdrop: true,
         
        filters : {
            // Maximum file size
            max_file_size : '10mb',
            // Specify what files to browse for
            mime_types: [
                {title : "Image files", extensions : "jpg,gif,png"}
            ]
        },
        
        rename: false,
        dragdrop: true,
        views: {
            list: false,
            thumbs: true, // Show thumbs
            active: 'thumbs'
        },
 
        // Resize images on clientside if we can
        resize: {
            width : 500,
            height : 500,
            quality : 90,
            crop: false // crop to exact dimensions
        },
 
 
        // Flash settings
        flash_swf_url : '',
     
        // Silverlight settings
        silverlight_xap_url : ''
    });
});