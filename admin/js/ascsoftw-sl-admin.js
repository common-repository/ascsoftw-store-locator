(function( $ ) {
	'use strict';

	$(document).ready(function() {

		ascsoftw_sl_sf_sr_change();
		ascsoftw_sl_result_change();
		ascsoftw_sl_zoom_change();
		ascsoftw_sl_map_type_change();
		ascsoftw_sl_full_screen_change();

		$('input[type=radio][name=ascsoftw_sl_sf_sr]').change(function() {
			ascsoftw_sl_sf_sr_change();
		});
		$('input[name=ascsoftw_sl_result_show]').change(function() {
			ascsoftw_sl_result_change();
		});

		$('input[type=radio][name=ascsoftw_sl_control_zoom]').change(function() {
			ascsoftw_sl_zoom_change();
		});
		$('input[type=radio][name=ascsoftw_sl_control_map_type]').change(function() {
			ascsoftw_sl_map_type_change();
		});
		$('input[type=radio][name=ascsoftw_sl_control_full_screen]').change(function() {
			ascsoftw_sl_full_screen_change();
		});

		//Require post title when adding/editing Project Summaries
		$( 'body' ).on( 'submit.edit-post', '#post', function () {

			// If the title isn't set
			if ( $( "#title" ).val().replace( / /g, '' ).length === 0 ) {

				// Hide the spinner
				$( '#major-publishing-actions .spinner' ).hide();

				// The buttons get "disabled" added to them on submit. Remove that class.
				$( '#major-publishing-actions' ).find( ':button, :submit, a.submitdelete, #post-preview' ).removeClass( 'disabled' );

				// Focus on the title field.
				$( "#title" ).css('border','3px solid #EC6239');
				$( "#title" ).focus();
				return false;
			}
		});

	});

	/**
	 * Search Radius Callback
	 *
	 * @since 1.0.0
	 */
	function ascsoftw_sl_sf_sr_change() {

		var val = $('input[name=ascsoftw_sl_sf_sr]:checked').val()
		if( val == 'yes') {
			$('.ascsoftw_sl_radius_search_unit').show();
		} else {
			$('.ascsoftw_sl_radius_search_unit').hide();
		}
	}

	/**
	 * Result Callback
	 *
	 * @since 1.0.0
	 */
	function ascsoftw_sl_result_change() {

		if($('input[name="ascsoftw_sl_result_show"]').is(':checked')) {
			$('.ascsoftw_sl_result_position').show();
			$('.ascsoftw_sl_result_sc').show();
			$('.ascsoftw_sl_result_sz').show();
			$('.ascsoftw_sl_result_ss').show();
			$('.ascsoftw_sl_result_sa2').show();
			$('.ascsoftw_sl_result_su').show();
			$('.ascsoftw_sl_result_bm').show();
			$('.ascsoftw_sl_result_om').show();
		} else {
			$('.ascsoftw_sl_result_position').hide();
			$('.ascsoftw_sl_result_sc').hide();
			$('.ascsoftw_sl_result_sz').hide();
			$('.ascsoftw_sl_result_ss').hide();
			$('.ascsoftw_sl_result_sa2').hide();
			$('.ascsoftw_sl_result_su').hide();
			$('.ascsoftw_sl_result_bm').hide();
			$('.ascsoftw_sl_result_om').hide();
		}
	}

	/**
	 * Zoom Callback
	 *
	 * @since 1.0.0
	 */
	function ascsoftw_sl_zoom_change() {

		var val = $('input[name=ascsoftw_sl_control_zoom]:checked').val()
		if( val == 'yes') {
			$('.ascsoftw_sl_zoom_position').hide();
		} else {
			$('.ascsoftw_sl_zoom_position').show();
		}
	}

	/**
	 * Map Type Callback
	 *
	 * @since 1.0.0
	 */
	function ascsoftw_sl_map_type_change() {

		var val = $('input[name=ascsoftw_sl_control_map_type]:checked').val()
		if( val == 'yes') {
			$('.ascsoftw_sl_maptype_position').hide();
		} else {
			$('.ascsoftw_sl_maptype_position').show();
		}
	}

	/**
	 * Full Screen Callback
	 *
	 * @since 1.0.0
	 */
	function ascsoftw_sl_full_screen_change() {

		var val = $('input[name=ascsoftw_sl_control_full_screen]:checked').val()
		if( val == 'yes') {
			$('.ascsoftw_sl_fullscreen_position').hide();
		} else {
			$('.ascsoftw_sl_fullscreen_position').show();
		}
	}


})( jQuery );

