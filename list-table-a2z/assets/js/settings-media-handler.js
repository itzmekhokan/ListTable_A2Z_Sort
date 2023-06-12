jQuery( function($){
	// on upload button click
	$( 'body' ).on( 'click', '.wpam-upload-image', function( event ){
		event.preventDefault(); // prevent default link click and page refresh
		
		const button = $(this)
		const imageId = button.siblings('.wpam-upload-id').val();
		
		const customUploader = wp.media({
			title: 'Insert image', // modal window title
			library : {
				// uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
				type : 'image'
			},
			button: {
				text: 'Use this image' // button label text
			},
			multiple: false
		}).on( 'select', function() { // it also has "open" and "close" events
			const attachment = customUploader.state().get( 'selection' ).first().toJSON();
            if ( attachment ) {
                button.siblings('.wpam-uploaded-img-wrapper').removeClass('none').addClass('active');
                button.siblings('.wpam-uploaded-img-wrapper').find('.wpam-upload-url').attr( 'src', attachment.url );
                button.siblings('.wpam-upload-id').val(attachment.id);
                button.hide();
            }
		})
		
		// already selected images
		customUploader.on( 'open', function() {

			if( imageId ) {
			  const selection = customUploader.state().get( 'selection' )
			  attachment = wp.media.attachment( imageId );
			  attachment.fetch();
			  selection.add( attachment ? [attachment] : [] );
			}
			
		})

		customUploader.open()
	
	});
	// on remove button click
	$( 'body' ).on( 'click', '.wpam-remove-image', function( event ){
		event.preventDefault();
		const button = $(this);
		button.parent().siblings('.wpam-upload-id').val('');
        button.parent().siblings('.wpam-upload-image').show();
        button.parent('.wpam-uploaded-img-wrapper').addClass('none').removeClass('active');
	});
});
