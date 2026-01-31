
jQuery( window ).load(function() {

	// Add the custom styles if the editor is active.
	if ( typeof tinyMCE !== 'undefined' && tinyMCE.activeEditor ) {
		tinyMCE.activeEditor.dom.addStyle( rtp_tinymce_css );
	}
});
