/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	//Special Font Color
	wp.customize( 'rtp_special_font_color', function( value ) {
		value.bind(function( to ) {
			$( '.rtp-special-title' ).css( 'color', to );
		});
	});

	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.rtp-site-title a' ).text( to );
		} );
	});

	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.rtp-site-description' ).text( to );
		} );
	});

	// Hide site title if logo is set
	wp.customize( 'custom_logo', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.rtp-site-title' ).addClass( 'rtp-hide-title' );
			} else {
				$( '.rtp-site-title' ).removeClass( 'rtp-hide-title' );
			}
		});
	});

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.rtp-site-title, .rtp-site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
				$( '.rtp-header-image' ).show();
			} else {
				$( '.rtp-site-title, .rtp-site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.rtp-header-image' ).hide();
			}
		});
	});

	//Header Image
	wp.customize( 'rtp_logo', function( value ) {
		value.bind( function( to ) {
			$( '.rtp-site-logo' ).attr( 'src', to );
		} );
	});

	//Header Text color
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' !== to ) {
				$( '.rtp-site-title a, .rtp-site-description' ).css( {
					'color': to
				});
			}
		});
	});

	//Background Image
	wp.customize( 'background_image', function( value ) {
		value.bind( function( to ) {
			$( 'body' ).css({
				'background-image': to
			});
		});
	});

	/*==============================
				SECTION 2
	===============================*/

	//Display Section
	wp.customize( 'home_section_2_visibility', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '#rtp-section-2' ).show();
			} else {
				$( '#rtp-section-2' ).hide();
			}
		});
	});

	//Description
	wp.customize( 'home_section_2_description', function( value ) {
		value.bind( function( to ) {
			$( '.rtp-section-2-title .rtp-heading-description' ).text( to );
		});
	});

	//Thumb Image 1
	wp.customize( 'home_section_2_thumb_1', function( value ) {
		value.bind( function( to ) {
			$( '.rtp-about-site-images' ).find( 'img:first-child' ).attr( 'src', to );
		});
	});

	//Thumb Image 2
	wp.customize( 'home_section_2_thumb_2', function( value ) {
		value.bind( function( to ) {
			$( '.rtp-about-site-images' ).find( 'img:last-child' ).attr( 'src', to );
		});
	});


	/*==============================
				SECTION 3
	===============================*/

	//Display Section
	wp.customize( 'home_section_3_visibility', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '#rtp-section-3' ).show();
			} else {
				$( '#rtp-section-3' ).hide();
			}
		});
	});

	//Description
	wp.customize( 'home_section_3_title', function( value ) {
		value.bind( function( to ) {
			$( '.rtp-section-3-title .rtp-special-title' ).text( to );
		});
	});

	//Description
	wp.customize( 'home_section_3_description', function( value ) {
		value.bind( function( to ) {
			$( '.rtp-section-3-title .rtp-heading-description' ).text( to );
		});
	});

	//Link Text
	wp.customize( 'home_section_3_linktext', function( value ) {
		value.bind( function( to ) {
			$( '#rtp-section-3 .rtp-readmore' ).text( to );
		});
	});

	//Background
	wp.customize( 'home_section_3_background', function( value ) {
		value.bind( function( to ) {
			$( '#rtp-section-3' ).css( 'background-image', 'url(' + to + ')' );
		});
	});

	/*==============================
				SECTION 4
	===============================*/

	//Display Section
	wp.customize( 'home_section_4_visibility', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '#rtp-section-4' ).show();
			} else {
				$( '#rtp-section-4' ).hide();
			}
		});
	});

	//Description
	wp.customize( 'home_section_4_title', function( value ) {
		value.bind( function( to ) {
			$( '.rtp-section-4-title .rtp-special-title' ).text( to );
		});
	});

	//Description
	wp.customize( 'home_section_4_description', function( value ) {
		value.bind( function( to ) {
			$( '.rtp-section-4-title .rtp-heading-description' ).text( to );
		});
	});

	//Link Text
	wp.customize( 'home_section_4_linktext', function( value ) {
		value.bind( function( to ) {
			$( '#rtp-section-3 .rtp-readmore' ).text( to );
		});
	});

	//Background
	wp.customize( 'home_section_4_background', function( value ) {
		value.bind( function( to ) {
			$( '#rtp-section-3' ).css( 'background-image', 'url(' + to + ')' );
		});
	});


	/*==============================
				SECTION 5
	===============================*/

	//Display Section
	wp.customize( 'home_section_5_visibility', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '#rtp-section-5' ).show();
			} else {
				$( '#rtp-section-5' ).hide();
			}
		});
	});

	//Description
	wp.customize( 'home_section_5_title', function( value ) {
		value.bind( function( to ) {
			$( '.rtp-section-5-title .rtp-special-title' ).text( to );
		});
	});

	//Description
	wp.customize( 'home_section_5_description', function( value ) {
		value.bind( function( to ) {
			$( '.rtp-section-5-title .rtp-heading-description' ).text( to );
		});
	});

	//Link
	wp.customize( 'home_section_5_link', function( value ) {
		value.bind( function( to ) {
			$( '#rtp-section-5 .rtp-readmore' ).attr( 'href', to );
		});
	});


	//Link Text
	wp.customize( 'home_section_5_linktext', function( value ) {
		value.bind( function( to ) {
			$( '#rtp-section-5 .rtp-readmore' ).text( to );
		});
	});

	//Background
	wp.customize( 'home_section_5_background', function( value ) {
		value.bind( function( to ) {
			$( '#rtp-section-5' ).css( 'background-image', 'url(' + to + ')' );
		});
	});

	/*==============================
	          Theme Color
	===============================*/

	var rtpColorSelectors = [
		'#secondary a',
		'.hentry a',
		'#comments a',
		'.rtp-main-navigation .current_page_item > a',
		'.rtp-main-navigation .current_page_ancestor > a',
		'.rtp-special-title',
		'.rtp-readmore',
		'.rtp-site-footer li.current_page_item a',
		'.rtp-section-4-thumbs li a'
	];

	var rtpBackgroundSelectors = 'button, input[type="button"], input[type="reset"], input[type="submit"]';

	var rtpHoverSelectors = [
		'a',
		'.rtp-main-navigation a',
		'.rtp-thumb-title a'
	];

	wp.customize( 'rtp_theme_color', function( value ) {
		value.bind( function( to ) {
			// Console.log(to, 'working');

			$.each( rtpColorSelectors, function( index, selector ) {
				$( selector ).css( 'color', to );
			});

			$( 'a, .rtp-main-navigation a,.rtp-thumb-title a ' ).hover(function( e ) {
				if ( e.type === 'mouseenter' ) {
					$( this ).css( 'color', to );
				} else {
					$( this ).css( 'color', '#fff' );
				}
			});

			$( rtpBackgroundSelectors ).css( 'background', to );

			$( '.rtp-main-navigation .current_page_item > a' ).css( 'box-shadow', 'inset 0 -3px 0 0 ' + to );

			$( '.rtp-main-navigation' ).find( 'ul.menu > li > a' ).hover(function( e ) {
			    $( this ).css( 'box-shadow', e.type === 'mouseenter' ? 'inset 0 -3px 0 0 ' + to : 'none' );
			});

			$( '.rtp-readmore' ).css( 'border-color', to );

		});
	});

	/*==============================
	          	BLOG
	===============================*/

	//Read More Text
	wp.customize( 'rtp_readmore_text', function( value ) {
		value.bind( function( to ) {
			$( '.entry-content .rtp-readmore' ).text( to );
		});
	});

	//Show Author
	wp.customize( 'rtp_show_post_author', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.rtp-meta-author-row' ).show();
			} else {
				$( '.rtp-meta-author-row' ).hide();
			}
		});
	});

	//Show Date
	wp.customize( 'rtp_show_post_date', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.rtp-meta-date-row' ).show();
			} else {
				$( '.rtp-meta-date-row' ).hide();
			}
		});
	});

	//Show Categories
	wp.customize( 'rtp_show_post_categories', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.rtp-meta-categories-row' ).show();
			} else {
				$( '.rtp-meta-categories-row' ).hide();
			}
		});
	});

	//Show Tags
	wp.customize( 'rtp_show_post_tags', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '#rtp-section-5' ).show();
			} else {
				$( '#rtp-section-5' ).hide();
			}
		});
	});

	//Show Comments
	wp.customize( 'rtp_show_post_comments', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.rtp-meta-comments-row' ).show();
			} else {
				$( '.rtp-meta-comments-row' ).hide();
			}
		});
	});

})( jQuery );
