jQuery( document ).ready( function() {
	Foodmania.init();
	if ( 'undefined' !== typeof bp && 'undefined' !== typeof bp.CoverImage && 'undefined' !== typeof bp.CoverImage.Attachment ) {
		bp.CoverImage.Attachment.on( 'change:url', function( data ) { 	jQuery( '.rtp-cover-img-set a img' ).prop( 'src', data.attributes.url ); } );
	}
} );

/**
 * Contains the custom scripts for the theme.
 */

( function( $ ) {

	'use strict';

	window.Foodmania = {
		init: function() {
			this.slideSettings();
			this.createMobleMenu();
			this.createBuddypressMenu();
			$( document ).foundation();
			this.loginpopup();
			this.closeloginpopup();
			this.loginForm();
			this.fixBuddypressHeaderButton();
			this.removeEmptyTextWidget();
			this.updatePostUpdatebutton();
		},
		slideSettings: function() {
			var $slideshow = $( '.cycle-slideshow' );

			$slideshow.css( 'visibility', 'visible' ).hide().fadeIn( 'fast', function() {
				$( '.rtp-main-slider' ).addClass( 'rtp-slider-loaded' );
			} );

			$( document.documentElement ).keyup( function( e ) {
				if ( e.keyCode == 39 ) {
					$slideshow.cycle( 'next' );
				}
				if ( e.keyCode == 37 ) {
					$slideshow.cycle( 'prev' );
				}
			} );
		},
		createMobleMenu: function() {
			$( '#masthead .rtp-site-title' ).clone( ).prependTo( '.rt-primary-menu' );

			$( '.rt-overlay' ).on( 'click', function(  ) {
				$( 'body' ).removeClass( 'animate-nav' );
			} );
                        
			$( '.menu-toggle' ).on( 'click', function( e ) {
				e.preventDefault();
				$( 'body' ).addClass( 'animate-nav' );
			} );

			$( '#primary-menu .menu-item-has-children > a' ).on( 'click', function( e ) {
				e.preventDefault();
				$( this ).toggleClass( 'rt-open' ).next().slideToggle();
			} );

		},
		createBuddypressMenu: function() {
			var $targetEl = $( '.rtp-cover-header-nav' );
			$targetEl.slicknav( {
				prependTo: '.rtp-cover-header-mobile-nav',
				label: 'Profile Menu'
			} );

			$( 'body' ).on( 'click', function() {
				$targetEl.slicknav( 'close' );
			} );

			$( '.slicknav_menu' ).on( 'click', function( e ) {
				e.stopPropagation();
			} );
		},
		loginpopup: function() {
			// popup Login form.
			$( '.rtp-login' ).on( 'click', function( e ) {
				e.preventDefault();
    	
				// Referenced these styles from inspirebook theme.
				const popup = document.getElementById('rtp-login-popup');
				popup.classList.add('open');
				popup.style.display='block';
				popup.style.opacity = 1;
				popup.style.visibility = 'visible';
				popup.style.top = '-100px';
			} );
		},
		closeloginpopup: function() {
			// popup Login form.
			$( '.rtp-close-reveal' ).on( 'click', function( e ) {
				e.preventDefault();
    	
				const popup = document.getElementById('rtp-login-popup');
				popup.classList.remove('open');
				popup.style.display='none';
				popup.style.opacity = 0;
				popup.style.visibility = 'none';
			} );
		},
		loginForm: function() {
			// Login form ajax
			$( '.rtp-login-form' ).on( 'submit', function( e ) {
				e.preventDefault();
				var $submitButton = $( this ).find( '.rtp-wp-submit' );
				$submitButton.val( 'Working...' );
				var data = $( '.rtp-login-form' ).serialize();
				$.post( foodmaniaVars.ajax_url, data, function( response ) {
					response = $.parseJSON( response );
					if ( response.error === false ) {
						$submitButton.val( 'Success. Just a moment...' );
						window.location.href = response.data;
					} else {
						var error = response.error_msg;
						$( '.rtp-login-form-container' ).not( '.rtp-get-error' ).addClass( 'rtp-get-error' ).prepend( '<p class="error">' + error + '</p>' );
						$submitButton.val( 'LOGIN' );
					}
				} );
			} );
		},
		fixBuddypressHeaderButton: function() {
			var $button = $( 'figure .bb_pc_rtmedia_change_cover_pic' );
			$( 'body' ).on( 'click', function() {
				$button.find( 'ul' ).hide();
			} );

			$button.on( 'click', function( e ) {
				e.stopPropagation();
			} );

		},
		removeEmptyTextWidget: function() {
			$( '.textwidget' ).each( function() {
				if ( $.trim( $( this ).html() ) === '' )
					$( this ).parents( '.widget_text' ).remove();
			} );
		},
		updatePostUpdatebutton: function() {
			var btnElem = $( '#whats-new-submit' );
			$( '#whats-new-post-in-box' ).after( btnElem );
		}
	};
        
        // Buddypress Friends remove veritical line
        var friends_list = jQuery('.widget_bp_core_friends_widget #friends-list-options');
        if( friends_list.length !== 0 ) {
            var innerHtml = friends_list.html();
            friends_list.html( innerHtml.replace( /\|/g, "" ) );
        }
        
} )( jQuery );


/*JQuery(window).scroll(function() {
if (jQuery(this).scrollTop() > 1){
    jQuery('header .rtp-main-header').addClass("sticky");
  }
  else{
    jQuery('header .rtp-main-header').removeClass("sticky");
  }
});*/
