/**
 *  Contains custom scripts for Rtcamp slider admin
 */

(function( $, window, undefined ) {

    'use strict';

    window.Rtcamp = {
                      Models: {},
                      Views: {}
                    };

	/*==============================
	        RTCAMP_ADMIN
	===============================*/

	Rtcamp.Models.Admin = Backbone.Model.extend({

	    defaults: {
			php_function: '',
			slide_title_name: '',
			slide_title: '',
			image_src: '',
			image_src_name: '',
			slide_content: '',
			slide_content_name: '',
			slide_permalink_name: '',
			slide_permalink: '',
			slide_number: '',
			total_slides: ''

	    },

	    url: function() {
 return ajaxurl + '?action=' + this.get( 'php_function' );
 }

	});

	Rtcamp.Views.Admin = Backbone.View.extend({

	    el: '#rtc-slider',

	    events: {
	    	'click .upload_image_button': 'createUploader',
	    	'click .add-new-button': 'addNewSlide',
	    	'click .delete-slide': 'deleteSlide',
	    	'keyup .slide-title .title': 'syncTitle',
	    	'click .remove-slider-image': 'removeImage',
	    	'submit #rtcamp-slider-form': 'onSubmit'
	    },

	    template: _.template( $( '#rtcamp-accordion-template' ).html() ),

	    initialize: function()
	    {
	    	this.$accordionDiv = $( '#rtcamp-accordion' );
	    	this.setExistingData();
	    	this.createAccordion();
	    	$( document ).ready(function() {
	    		$( '.slider-preview' ).fadeIn( false, function() {
	    			$( this ).css( 'visibility', 'visible' );
    				if ( ! $( this ).find( 'img' ).attr( 'src' ) ) {
    					$( this ).hide();
    				}
	    		});
	    	});
	    },

	    render: function()
	    {
	    	 var $markup = this.template( this.model.toJSON() );
	    	 this.$accordionDiv.append( $markup );
	    	 return this;
	    },

	    refresh: function()
	    {
	    	this.$accordionDiv.accordion( 'destroy' ).sortable( 'destroy' );
	    	this.createAccordion();
	    	return this;
	    },

	    setExistingData: function()
	    {
			var _this      = this,
			$tableRows     = this.$( '.form-table' ).find( 'tr' ),
			$slideFields;

	    	this.model.set( 'total_slides', $tableRows.length );

	    	$tableRows.each(function( key )
	    	{
	    		$slideFields = $( this ).find( '.slide-fields' );

	    		 _this.model
	    		 .set( 'image_src', $slideFields.find( '.image-src-input' ).val()  )
	    		 .set( 'slide_title', $slideFields.find( '.title-input' ).val() 	 	)
	    		 .set( 'slide_content', $slideFields.find( '.content-input' ).val()    )
	    		 .set( 'slide_permalink', $slideFields.find( '.permalink-input' ).val()  )
	    		 ;

	    		 _this.setInputNames.call( _this, key ).render.call( _this );

	    		 $( this ).remove(); //Now lets get rid of it from DOM

	    		 if ( _this.model.get( 'image_src' ) ) //Also show the slides which have images.
	    		 	_this.$( '.group[data-slide-number="' + key + '"]' ).find( '.slider-preview' ).show();

	    	});

	    },

	    setInputNames: function( key )
	    {
	    	var option_name = rtc_slide_data.slider_options_name;
	    	this.model
	    	.set( 'image_src_name', option_name + '[slides][slide_number][image_src]'.replace( 'slide_number', key ) )
	    	.set( 'slide_title_name', option_name + '[slides][slide_number][title]'.replace( 'slide_number', key ) 	)
	    	.set( 'slide_content_name', option_name + '[slides][slide_number][content]'.replace( 'slide_number', key ) 	)
	    	.set( 'slide_permalink_name', option_name + '[slides][slide_number][permalink]'.replace( 'slide_number', key ) )
	    	.set( 'slide_number', key );

	    	return this;
	    },

	    createUploader: function( e )
	    {
    	    e.preventDefault();

    	    var custom_uploader, $this, $currentGroup, attachment, $sliderPreview, $previewImage;

			$this         = $( e.currentTarget );
			$currentGroup = $this.closest( '.group' );

    	    //If the uploader object has already been created, reopen the dialog
    	    if ( custom_uploader ) {
    	        custom_uploader.open();
    	        return;
    	    }

    	    //Extend the wp.media object
    	    custom_uploader = wp.media.frames.file_frame = wp.media({
    	        title: rtc_slide_data.choose_slider_image_text,
    	        button: {
    	            text: rtc_slide_data.choose_slider_image_text
    	        },
    	        multiple: false
    	    });

    	    //When a file is selected, grab the URL and set it as the text field's value
    	    custom_uploader.on( 'select', function()
    	    {
    	        attachment = custom_uploader.state().get( 'selection' ).first().toJSON();

    	        $currentGroup.find( '.rtc-choose-image' ).val( attachment.url );
				$sliderPreview = $currentGroup.find( '.slider-preview' ).fadeIn( false, function() {
					$( this ).css( 'visibility', 'visible' );
				});
				$previewImage  = $sliderPreview.find( 'img.slider-image' );

    	        if ( $previewImage.length ) {
    	        	$previewImage.attr( 'src', attachment.url );
    	        } else {
    	        	$sliderPreview.html( '<img class="slider-image" src="' + attachment.url + '">' );
    	        }
    	    });

    	    //Open the uploader dialog
    	    custom_uploader.open();

	    },

	    createAccordion: function()
	    {
	    	var _this = this;

	    	this.$accordionDiv
	    	.accordion({
	    	  header: '.header',
	    	  heightStyle: 'content',
	    	  collapsible: true
	    	})
	    	.sortable({
	    	  axis: 'y',
	    	  handle: _this.$( '.header' ),
	    	  stop: function( event, ui )
	    	  {
	    	    ui.item.children( '.header' ).triggerHandler( 'focusout' );

	    	    // Refresh accordion to handle new order
	    	    $( this ).accordion( 'refresh' );
	    	    _this.resetSlideNames.call( _this );

	    	  }
	    	});
	    },

	    addNewSlide: function( e )
	    {
	    	e.preventDefault();

	    	var totalSlides = this.model.get( 'total_slides' );

	    	for ( var property in this.model.toJSON() ) {
	    		this.model.set( property, '' );
	    	}

	    	this.setInputNames( totalSlides )
	    	.model.set( 'total_slides', totalSlides + 1 );

	    	this.render().refresh();

    		this.$accordionDiv.find( '.group:last-child .header' ).click();

	    	$( 'html, body' ).animate({ scrollTop: $( document ).height() }, 400 );
	    },

	    resetSlideNames: function()
	    {
	    	var _this = this;
	    	this.$accordionDiv.find( '.group' ).each(function( key ) {
	    		_this.setInputNames.call( _this, key );

	    		$( this ).find( 'input.rtc-choose-image' ).attr( 'name', _this.model.get( 'image_src_name' ) );
	    		$( this ).find( 'input.title' ).attr( 'name', _this.model.get( 'slide_title_name' ) );
	    		$( this ).find( 'texarea.content' ).attr( 'name', _this.model.get( 'slide_content_name' ) );
	    		$( this ).find( 'input.permalink' ).attr( 'name', _this.model.get( 'slide_permalink_name' ) );

	    	});

	    },

	    deleteSlide: function( e )
	    {
	    	e.preventDefault();

	    	if ( ! confirm( rtc_slide_data.delete_confirm_text  ) ) return;
			var $this     = $( e.currentTarget ),
			_this         = this,
			$currentGroup = $this.closest( '.group' );

	    	$currentGroup.slideUp( 'slow', function() {
	    		$( this ).remove();
	    		_this.resetSlideNames();
	    	});

	    },

	    syncTitle: function( e )
	    {
	    	var $this = $( e.currentTarget );
	    	$this.closest( '.group' ).find( 'h3' ).text( $this.val() );
	    },

	    removeImage: function( e )
	    {
	    	e.preventDefault();
	    	var $this = $( e.currentTarget ),
	    	$currentGroup = $this.closest( '.group' );
	    	$currentGroup.find( '.slider-preview' ).hide().find( '.slider-image' ).attr( 'src', '' );
	    	$currentGroup.find( '.rtc-choose-image' ).val( '' );
	    },

	    onSubmit: function( e ) {
    		var hasImages = false;

	    	$( '#rtcamp-accordion' ).find( '.group' ).each(function() {
	    		if ( $.trim( $( this ).find( '.rtc-choose-image' ).val() ) ) {
	    			hasImages = true;
	    		}
	    	});

	    	//If they insert even one image, they should insert all.
	    	if ( hasImages )
	    	{
				$( '#rtcamp-accordion' ).find( '.group' ).each(function() {
					if ( ! $.trim( $( this ).find( '.rtc-choose-image' ).val() ) ) {
						e.preventDefault();
						alert( rtc_slide_data.no_image_error_text );
						return false;
					}
				});
	    	} else {
	    		$( '#rtcamp-accordion' ).find( '.group' ).each(function() {
	    			if ( ! $.trim( $( this ).find( '.title' ).val() ) ) {
	    				e.preventDefault();
	    				alert( rtc_slide_data.no_title_error_text );
	    				return false;
	    			}
	    		});
	    	}

	    }

	});

	Rtcamp.Admin = new Rtcamp.Views.Admin( { model: new Rtcamp.Models.Admin() } );

})( jQuery, window, undefined );

