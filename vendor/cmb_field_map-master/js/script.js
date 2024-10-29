(function( $ ) {
	'use strict';

	var maps = [];
	var ascsoftw_places;

	$( '.cmb-type-pw-map' ).each( function() {
		initializeMap( $( this ) );
	});

	$('.pw-update-address').click( function () {
		var address = {};
		ascsoftw_places.address_components.forEach(function(c) {
			c.types.forEach(function(a) {
				switch(a){
					case 'street_number':
						address.address = c.long_name;
						break;
					case 'route':
						address.address2 = c.long_name;
						break;
					case 'sublocality_level_1':
						if( !address.hasOwnProperty("address") ) {
							console.log('at 26');
							address.address = c.long_name
						}
						break;
					case 'sublocality_level_2':
						if( !address.hasOwnProperty("address2") ) {
							address.address2 = c.long_name
						}
						break;
					case 'neighborhood': 
					case 'locality':
						address.City = c.long_name;
						break;
					case 'administrative_area_level_1':
						address.State = c.long_name;
						break;
					case 'postal_code':
						address.Zip = c.long_name;
						break;
					case 'country':
						address.Country = c.long_name;
						break;
				}
			});
		});

		if( address.hasOwnProperty("address") ) {
			$('#ascsoftw_sl_address').val( address.address);
		}

		if( address.hasOwnProperty("address2") ) {
			$('#ascsoftw_sl_address_2').val( address.address2);
		}

		if( address.hasOwnProperty("City") ) {
			$('#ascsoftw_sl_city').val( address.City);
		}

		if( address.hasOwnProperty("State") ) {
			$('#ascsoftw_sl_state').val( address.State);
		}

		if( address.hasOwnProperty("Zip") ) {
			$('#ascsoftw_sl_zipcode').val( address.Zip);
		}

		if( address.hasOwnProperty("Country") ) {
			$('#ascsoftw_sl_country').val( address.Country);
		}
		$('.pw-update-address').hide();
	});

	function initializeMap( mapInstance ) {
		var searchInput = mapInstance.find( '.pw-map-search' );
		var mapCanvas = mapInstance.find( '.pw-map' );
		var latitude = mapInstance.find( '.pw-map-latitude' );
		var longitude = mapInstance.find( '.pw-map-longitude' );
		var latLng = new google.maps.LatLng( 54.800685, -4.130859 );
		var zoom = 5;

		// If we have saved values, let's set the position and zoom level
		if ( latitude.val().length > 0 && longitude.val().length > 0 ) {
			latLng = new google.maps.LatLng( latitude.val(), longitude.val() );
			zoom = 17;
		}

		// Map
		var mapOptions = {
			center: latLng,
			zoom: zoom
		};
		var map = new google.maps.Map( mapCanvas[0], mapOptions );

		var geocoder = new google.maps.Geocoder;

		// Marker
		var markerOptions = {
			map: map,
			draggable: true,
			title: 'Drag to set the exact location'
		};
		var marker = new google.maps.Marker( markerOptions );

		if ( latitude.val().length > 0 && longitude.val().length > 0 ) {
			marker.setPosition( latLng );
			geocodeLatLng( latitude.val(), longitude.val(), geocoder );
		}

		// Search
		var autocomplete = new google.maps.places.Autocomplete( searchInput[0] );
		autocomplete.bindTo( 'bounds', map );

		google.maps.event.addListener( autocomplete, 'place_changed', function() {
			var place = autocomplete.getPlace();
			if ( ! place.geometry ) {
				return;
			}

			if ( place.geometry.viewport ) {
				map.fitBounds( place.geometry.viewport );
			} else {
				map.setCenter( place.geometry.location );
				map.setZoom( 17 );
			}
			$('.pw-update-address').show();
			ascsoftw_places = place;

			marker.setPosition( place.geometry.location );

			latitude.val( place.geometry.location.lat() );
			longitude.val( place.geometry.location.lng() );
		});

		$( searchInput ).keypress( function( event ) {
			if ( 13 === event.keyCode ) {
				event.preventDefault();
			}
		});

		// Allow marker to be repositioned
		google.maps.event.addListener( marker, 'dragend', function() {
			latitude.val( marker.getPosition().lat() );
			longitude.val( marker.getPosition().lng() );
			geocodeLatLng( marker.getPosition().lat(), marker.getPosition().lng(), geocoder );			
		});

		maps.push( map );
	}

	// Resize map when meta box is opened
	if ( typeof postboxes !== 'undefined' ) {
		postboxes.pbshow = function () {
			var arrayLength = maps.length;
			for (var i = 0; i < arrayLength; i++) {
				var mapCenter = maps[i].getCenter();
				google.maps.event.trigger(maps[i], 'resize');
				maps[i].setCenter(mapCenter);
			}
		};
	}

	// When a new row is added, reinitialize Google Maps
	$( '.cmb-repeatable-group' ).on( 'cmb2_add_row', function( event, newRow ) {
		var groupWrap = $( newRow ).closest( '.cmb-repeatable-group' );
		groupWrap.find( '.cmb-type-pw-map' ).each( function() {
			initializeMap( $( this ) );
		});
	});

	function geocodeLatLng(lat, long, geocoder) {
		var currentPlaceByMarker = '';
		var latlng = {lat: parseFloat(lat), lng: parseFloat(long)};
		geocoder.geocode({'location': latlng}, function(results, status) {
			if (status === 'OK') {
				if (results[0]) {
					currentPlaceByMarker = results[0].formatted_address; 
				}
			}
			jQuery('.pw-map-search').val(currentPlaceByMarker);
		});
	}

})( jQuery );