jQuery(document).ready(function($){
 
    var pc8_uploader;
 
    $('#pc8_upload_button').click(function(e) {
 
        e.preventDefault();
 
        if (pc8_uploader) {
            pc8_uploader.open();
            return;
        }
 
        pc8_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
       /* set URL to input */
        pc8_uploader.on('select', function() {
            attachment = pc8_uploader.state().get('selection').first().toJSON();
            $('#pc8_image_url').val(attachment.url);
        });
 
        /* Open Uploader */
        pc8_uploader.open();
 
    });
});

