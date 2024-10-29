var ascsoftw_sl_map;
var ascsoftw_sl_markers = [];
var ascsoftw_sl_animate_id;
var ascsoftw_origin;
var ascsoftw_initial_location;

(function( $ ) {
	'use strict';
	 $(document).ready(function() {

		ascsoftw_sl_map = new google.maps.Map( document.getElementById("ascsoftw_sl_map"), ascsoftw_map_options );
		var input = document.getElementById('ascsoftw_sl_searchtext');
		var searchBox = new google.maps.places.SearchBox(input);

		searchBox.addListener('places_changed', function() {
			var places = searchBox.getPlaces();  

			if (places.length == 0) {
				return;
			}

			places.forEach(function(place) {

				if (!place.geometry) {
					return;
				}

				try {
					ascsoftw_initial_location = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());
					ascsoftw_sl_map.setCenter(ascsoftw_initial_location);
				} catch (error) {

				}
			});
			ascsoftw_get_stores();
		});

		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				ascsoftw_initial_location = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
				ascsoftw_origin = position.coords.latitude + ',' + position.coords.longitude;
				ascsoftw_sl_map.setCenter(ascsoftw_initial_location);
				ascsoftw_get_stores();
			},
			function(error) {
				ascsoftw_fix_position(); 
			});
		} else {
			ascsoftw_fix_position();
		}

		if( ascsoftw_result_options.bounce == 1 ) {
			$(document).on('mouseenter', '.ascsoftw_sl_result', function(){
				var aId = $(this).attr('id');
				ascsoftw_sl_animate_id = aId.replace('ascsoftw_sl_loc_', '');
				ascsoftw_sl_markers[ascsoftw_sl_animate_id].setAnimation(google.maps.Animation.BOUNCE);
			}).on('mouseleave','.ascsoftw_sl_result', function(){
				ascsoftw_sl_markers[ascsoftw_sl_animate_id].setAnimation(null);
			});
		}
	});
})( jQuery );

/**
 * Fix the Center of the Map ourselves since User Location is not available..
 *
 * @since 1.0.0
 */
function ascsoftw_fix_position() {
	var firstLat = jQuery('#ascsoftw_sl_f_lat').val();
	var firstLong = jQuery('#ascsoftw_sl_f_long').val();
	if( firstLat.length > 0 && firstLong.length > 0 ) {
		ascsoftw_initial_location = new google.maps.LatLng( firstLat, firstLong);
		ascsoftw_sl_map.setCenter(ascsoftw_initial_location);
		ascsoftw_get_stores();
	} else {
		ascsoftw_initial_location = new google.maps.LatLng( 30.72442255774446, -76.80664611719658);
		ascsoftw_sl_map.setCenter(ascsoftw_initial_location);
		ascsoftw_sl_map.setZoom(14);
		ascsoftw_get_stores();
	}
}

/**
 * Create the Markers on Google Maps.
 *
 * @since 1.0.0
 */
function ascsoftw_create_markers(){
	var infowindow = new google.maps.InfoWindow();
	var i;
	var bounds = new google.maps.LatLngBounds();

	for (i = 0; i < ascsoftw_locations.length; i++) {
		var myLatLng = new google.maps.LatLng( ascsoftw_locations[i].attributes.lat, ascsoftw_locations[i].attributes.long );
		var marker = new google.maps.Marker({
			position: myLatLng,
			map: ascsoftw_sl_map
		});
		bounds.extend(myLatLng);
		google.maps.event.addListener(marker, "click", (function(marker, i) {
			return function() {
				infowindow.setContent( ascsoftw_locations[i].infowindow_html );
				infowindow.open(ascsoftw_sl_map, marker);
				ascsoftw_make_active( ascsoftw_locations[i].id );
 
			}
		})(marker, i));
		ascsoftw_sl_markers[ascsoftw_locations[i].id] = marker;
	}
	bounds.extend( ascsoftw_initial_location );
	ascsoftw_sl_map.fitBounds( bounds );
	ascsoftw_sl_map.setCenter( bounds.getCenter() );
}

/**
 * Clear all the Markers on Google Maps.
 *
 * @since 1.0.0
 */
function ascsoftw_clear_all_markers() {
	ascsoftw_sl_markers.forEach(function(marker) {
		marker.setMap(null);
	  });
	  ascsoftw_sl_markers = [];
}

/**
 * Remove all Results from the Listing.
 *
 * @since 1.0.0
 */
function ascsoftw_remove_all_listings() {
	jQuery('#ascsoftw_sl_list').html('');
}

/**
 * Display the Results in Listing
 *
 * @since 1.0.0
 */
function ascsoftw_show_listings( ) {

	if( ascsoftw_result_options.show == 1 ) {
		var post_template = wp.template( 'ascsoftw-sl-result' );
		jQuery.each( ascsoftw_locations, function (i, val) { 
			jQuery( '#ascsoftw_sl_list' ).append( post_template( val ) );
		});
	}
}

/**
 * Open the Marker in Google Maps
 *
 * @since 1.0.0
 * @param  object   obj JS object which was clicked.
 * @param  number   id Id of the clicked result.
 */
function ascsoftw_show_location_in_map(obj, id){
	if( ascsoftw_result_options.open_marker == 0 ) {
		return;
	}
	jQuery('.ascsoftw_sl_result').removeClass('active');
	jQuery(obj).parent().parent().addClass('active');
	google.maps.event.trigger(ascsoftw_sl_markers[id], 'click');
}

/**
 * Make the Result in Listing Active.
 *
 * @since 1.0.0
 * @param  number   id Id of the result which needs to be made active.
 */
function ascsoftw_make_active( id ) {
	if( ascsoftw_result_options.show == 1 ) {

		jQuery('.ascsoftw_sl_result').removeClass('active');
		jQuery('#ascsoftw_sl_loc_'+id).addClass('active');

		var listingOffset = document.getElementById('ascsoftw_sl_result_listing').offsetTop;
		var topPos = document.getElementById('ascsoftw_sl_loc_'+id).offsetTop;
		document.getElementById('ascsoftw_sl_result_listing').scrollTop = topPos - listingOffset;
	}
}

/**
 * AJAX Call to get all the results.
 *
 * @since 1.0.0
 */
function ascsoftw_get_stores() {

	jQuery('.ascsoftw_sl_ss_row').css('visibility', 'hidden');
	jQuery('.ascsoftw_sl_spinner').css('visibility', 'visible');
	var max_results   = jQuery('#ascsoftw_sl_max').val();
	var category_id   = jQuery('#ascsoftw_sl_cats').val();
	var distance      = jQuery('#ascsoftw_sl_radius').val();
	var distance_unit = jQuery('#ascsoftw_sl_distance_unit').val();
	jQuery('#ascsoftw_sl_no_results').hide();

	var data = {
		'action'        : 'get_store_search',
		'distance'      : distance,
		'distance_unit' : distance_unit,
		'lat'           : ascsoftw_initial_location.lat(),
		'long'          : ascsoftw_initial_location.lng(),
		'category_id'   : category_id,
		'max_results'   : max_results,
		'security'      : jQuery( '#ascsoftw_sl_ajax_nonce' ).val(),
	};

	var url     = jQuery( '#ascsoftw_admin_url' ).val();
	var ajaxurl = url + 'admin-ajax.php';

	jQuery.post( ajaxurl, data, function( response ) {
		ascsoftw_locations = JSON.parse( response );
		jQuery('.ascsoftw_sl_spinner').css('visibility', 'hidden');
		ascsoftw_clear_all_markers();
		ascsoftw_remove_all_listings();
		if( ! ascsoftw_locations.length ) {
			jQuery('#ascsoftw_sl_no_results').show();
		} else {
			jQuery('.ascsoftw_sl_ss_row').css('visibility', 'visible');
			jQuery('#ascsoftw_sl_sfss').html(ascsoftw_locations.length);
			ascsoftw_show_listings();
			ascsoftw_create_markers();
		}
	});
}

/**
 * Create and Redirect to the Get Directions Link
 *
 * @since 1.0.0
 * @param  number   id Id of the destination result.
 */
function ascsoftw_get_directions(id) {

	var origin_coordinates = ascsoftw_get_origin();
	var url = "https://www.google.com/maps/dir/?api=1";
	var origin = "&origin=" + origin_coordinates;
	var destination = "&destination=" + ascsoftw_sl_markers[id].position.lat() + "," + ascsoftw_sl_markers[id].position.lng();
	var newUrl = new URL(url + origin + destination);
	var win = window.open(newUrl, '_blank');
	win.focus();
}

/**
 * Get the Latitude and Longitude of the Origin.
 *
 * @since 1.0.0
 */
function ascsoftw_get_origin() {

	try {
		return ascsoftw_initial_location.lat() + ',' + ascsoftw_initial_location.lng();
	} catch (e) {
		return ascsoftw_sl_map.getCenter().lat() + ',' + ascsoftw_sl_map.getCenter().lng();
	}
}
