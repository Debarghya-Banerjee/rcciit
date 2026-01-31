/**
 * Contains the custom scripts for the theme.
 */

(function( $ ) {

    'use strict';

    window.FoodmaniaAdmin = {

        init: function() {
        	this.createUploader();
        	this.removeBannerImage();
        	this.hideShowMetaBoxes();
		},

		createUploader: function() {

			$( 'body' ).on( 'click', '#foodmania-upload-banner', function( e ) {

				e.preventDefault();

				var custom_uploader, attachment;

				//If the uploader object has already been created, reopen the dialog
				if ( custom_uploader ) {
				    custom_uploader.open();
				    return;
				}

				//Extend the wp.media object
				custom_uploader = wp.media.frames.file_frame = wp.media({
				    title: 'Choose Banner',
				    button: {
				        text: 'Choose Banner'
				    },
				    multiple: false
				});

				//When a file is selected, grab the URL and set it as the text field's value
				custom_uploader.on( 'select', function()
				{
				    attachment = custom_uploader.state().get( 'selection' ).first().toJSON();

				    if ( attachment.url ) {
				    	$( '#foodmania-banner-image' ).html( '<img src=\'' + attachment.url + '\' alt=\'banner-image\' width=\'100%\' >' );
				    	$( '#foodmania_banner_image_field' ).val( attachment.url );
				    	$( '.foodmania-banner-links' ).html( '<a id="foodmania-remove-banner" href="" >Remove banner image</a>' );
				    }
				});

				//Open the uploader dialog
				custom_uploader.open();

				});
		},

		removeBannerImage: function() {
			$( 'body' ).on( 'click', '#foodmania-remove-banner', function( e ) {
				e.preventDefault();
				$( '#foodmania-banner-image' ).html( '' );
				$( '.foodmania-banner-links' ).html( '<a id="foodmania-upload-banner" href="" >Set banner image</a>' );
				$( '#foodmania_banner_image_field' ).val( '' );
			});

		},

		hideShowMetaBoxes: function() {
			var $select = $( '#pageparentdiv' ).find( '#page_template' );
			var template = 'page-templates/template-home.php';
			var $metaBoxes = $( '#foodmania_banner_image, #foodmania_banner_description' );

			if (  ! $metaBoxes ) return;

			$select.on( 'change', function() {
				if ( $( this ).val() === template ) {
					$metaBoxes.hide();
				} else {
					$metaBoxes.show();
				}
			});
		}

    };

    $( document ).ready(function() {
    	FoodmaniaAdmin.init();
    });

})( jQuery );
