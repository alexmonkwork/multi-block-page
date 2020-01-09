(function( $ ) {
	$('.color-field').each(function()
	{
		$(this).wpColorPicker();
	});

	$('body').on('click', '.mbp_upload_image', function(e)
	{
		e.preventDefault();

		var button = $(this),
			custom_uploader = wp.media({
				title: 'Insert image',
				library : {
					type : 'image'
				},
				button: {
					text: 'Use this image'
				},
				multiple: false
			}).on('select', function() { // it also has "open" and "close" events
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				$(button).removeClass('button').append('<img class="true_pre_image" src="' + attachment.url + '" style="max-width:95%;display:block;" />').next().val(attachment.id).next().show();
			})
				.open();
	});

	/*
     * Remove image event
     */
	$('body').on('click', '.mbp_remove_image', function()
	{
		$(this).hide().prev().val('').prev().addClass('button').html('Upload image');
		return false;
	});

})( jQuery );