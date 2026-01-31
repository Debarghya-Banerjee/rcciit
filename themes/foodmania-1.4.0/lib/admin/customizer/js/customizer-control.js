/**
 *  Contains custom scripts
 */

(function( $, window, undefined ) {

    'use strict';

    window.Rtpcc = {
                      Models: {},
                      Views: {}
                    };


	/*==============================
                FONT
    ===============================*/

    Rtpcc.Models.Font = Backbone.Model.extend({

        defaults: {
            php_function: '',
            font_value: ''
        }

    });

    Rtpcc.Views.Font = Backbone.View.extend({

        // El : '#accordion-panel-rtp_general_panel', // default
        el: '#sub-accordion-section-rtp_fonts_section',

        events: {
        	'change #customize-control-rtp_special_font select': 'loadSpecialFontSubset',
        	'change #customize-control-rtp_body_font select': 'loadBodyFontSubset'
        },

        initialize: function() {

        },

        loadSpecialFontSubset: function( e )
        {
        	this.load( e, 'rtp_special_font', 'rtp_special_font_variant', 'rtp_special_font_subset' );
        },

        loadBodyFontSubset: function( e )
        {
        	this.load( e, 'rtp_body_font', 'rtp_body_font_variant', 'rtp_body_font_subset' );
        },

        load: function( e, $fontKey, $variantKey, $subsetKey )
        {
            var $this  = e ? $( e.currentTarget ) : this.obj( $fontKey ),
            $option    = false,
            _this      = this,
            $variantEl = this.obj( $variantKey ),
            $subsetEl  = this.obj( $subsetKey );

            $.ajax({
              url: ajaxurl + '?action=rtp_load_variants_subsets',
              data: {
                 'font_value': $this.val(),
                 'rtp_security_nonce' : rtp_ajax_data.rtp_ajax_nonce
              }
            }).done(function( resp ) {
                resp = JSON.parse( resp );
              _this.fillOptions( $variantEl, resp.variants );
              _this.fillOptions( $subsetEl, resp.subsets );
            });
        },

        obj: function( $key )
        {
            return $( '#customize-control-' + $key ).find( 'select' );
        },

        fillOptions: function( $el, $obj )
        {
            if ( ! $el || ! $obj ) return;

            $el.empty(); // Remove old options
            $.each( $obj, function( value, key ) {
              $el.append( $( '<option></option>' ).attr( 'value', key ).text( key ) );
            });
        }

    });

    Rtpcc.Views.SectionPosition = Backbone.View.extend({

        el: '#accordion-panel-rtp_home_section_panel',

        events: {
            'click .button-primary.save': 'alertPositionValues'
        },

        alertPositionValues: function() {

        }

    });

    $( document ).ready(function() {
    	new Rtpcc.Views.Font( { model: new Rtpcc.Models.Font() } );
        new Rtpcc.Views.SectionPosition();
    });

})( jQuery, window, undefined );

