<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui, shrink-to-fit=no">

<style>

.AvisoMapa {
    color: #fff;
    position: absolute;
    background: #006699;
	padding: 5px 5px;
    border-radius: 4px;
    z-index: 1000;
    box-shadow: 0 1px 10px rgba(0,0,0,0.19), 0 2px 4px rgba(0,0,0,0.23);
    text-align: center;
    font-size: 14px;
    line-height: 15px;
	top: 50px; 
	left: 10px; 
	z-index: 99;
	width: 70%;
	top: 50px;
    left: 15%;
    right: 0;
	font-family: Arial;
    font-size: 11px;
}

#pac-input {
	background-color: #fff;
	font-family: Roboto;
	font-size: 12px;
	font-weight: 300;
	margin-left: 12px;
	padding: 0 11px 0 13px;
	text-overflow: ellipsis;
	width: 310px;
    text-align: center;
	height: 20px;
    box-shadow: 2px 4px 10px 0 rgba(0, 0, 0, 0.2);
    border: 1px solid #ccc;
    border-radius: 20px;
}

</style>

<script src="../../html_public/js/lib/jquery-3.2.1.min.js"></script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $conf_general_key_google; ?>&amp;libraries=places"></script>

<script type="text/javascript">
        function UpdateMapaDragend(newLat, newLng)
        {
                $('#mis_coordenadas_mapa').val(newLat + ',' + newLng);
                
                parent.actualizar_geo_dom(newLat + ',' + newLng);
        }

        function UpdateMapaClick(newLat, newLng)
        {
                var marker_icon = {
                        url: "../../html_public/imagenes/marker.png"};

                var markerOptions = {
                map: map,
                position: new google.maps.LatLng(newLat, newLng),
                draggable: true,
                icon: marker_icon,
                animation:  google.maps.Animation.DROP		
                };
                marker_0 = createMarker_map(markerOptions);

                marker_0.set("content", "<div style=\"text-align: center;\"> <span style=\"font-size: 20px; font-weight: bold;\">  <i class='fa fa-flag-o' aria-hidden='true'></i> Geolocalización del Solicitante </span> <br /> <span> Ubique este pin donde lo requiera </span> </div>");

                google.maps.event.addListener(marker_0, "click", function(event) {
                        iw_map.setContent(this.get("content"));
                        iw_map.open(map, this);

                });

                google.maps.event.addListener(marker_0, "dragstart", function(event) {
                        $('.AvisoMapa').fadeOut(400);
                });

                google.maps.event.addListener(marker_0, "dragend", function(event) {
                        UpdateMapaDragend(event.latLng.lat(), event.latLng.lng());
                });

                $('.AvisoMapa').text("Mueve el pin hasta la ubicación exacta");
                $('.AvisoMapa').fadeIn(400);
                $('#mis_coordenadas_mapa').val(newLat + ',' + newLng);
                parent.actualizar_geo_dom(newLat + ',' + newLng);
        }

        function BusquedaLugares()
        {

                var input2 = document.getElementById('pac-input');
                var searchBox2 = new google.maps.places.SearchBox(input2);

                searchBox2.addListener('places_changed', function() {
                  var places = searchBox2.getPlaces();

                  if (places.length == 0) {
                        return;
                  }

                  // For each place, get the icon, name and location.
                  var bounds = new google.maps.LatLngBounds();

                  places.forEach(function(place) {
                        if (!place.geometry) {
                          console.log("Returned place contains no geometry");
                          return;
                        }

                        if (place.geometry.viewport) {
                          // Only geocodes have viewport.
                          bounds.union(place.geometry.viewport);
                        } else {
                          bounds.extend(place.geometry.location);
                        }
                  });
                  map.fitBounds(bounds);
                });

        }

        //<![CDATA[

        var map; // Global declaration of the map
        var lat_longs_map = new Array();
        var markers_map = new Array();
        var iw_map;
        var placesService;
        var placesAutocomplete;

        iw_map = new google.maps.InfoWindow();

        function initialize_map() {

                BusquedaLugares();

                var myLatlng = new google.maps.LatLng(<?php echo $coordenadas_geo_dom; ?>);
                var myOptions = {
                        zoom: 15,
                        center: myLatlng,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        disableDoubleClickZoom: true,
                        disableDefaultUI: true,
                        zoomControl: true,
                        scaleControl: true,
                        streetViewControl: false,
                        styles: [
                            {
                                  "featureType": "administrative.land_parcel",
                                  "stylers": [
                                    {
                                          "visibility": "off"
                                    }
                                  ]
                            },
                            {
                                  "featureType": "landscape.man_made",
                                  "stylers": [
                                    {
                                          "visibility": "off"
                                    }
                                  ]
                            },
                            {
                                  "featureType": "poi",
                                  "stylers": [
                                    {
                                          "visibility": "off"
                                    }
                                  ]
                            },
                            {
                                  "featureType": "transit",
                                  "stylers": [
                                    {
                                          "visibility": "off"
                                    }
                                  ]
                            }
                          ]
                        }
                map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
                var placesRequest = {
                        location: new google.maps.LatLng(0, 0)
                                ,radius: 10
                                };


                placesService = new google.maps.places.PlacesService(map);
                placesService.search(placesRequest, placesCallback);
                var autocompleteOptions = {
                        }
                var autocompleteInput = document.getElementById('pac-input');

                map.controls[google.maps.ControlPosition.TOP_CENTER].push(autocompleteInput);

                placesAutocomplete = new google.maps.places.Autocomplete(autocompleteInput, autocompleteOptions);
                placesAutocomplete.bindTo('bounds', map);
                        google.maps.event.addListener(placesAutocomplete, 'place_changed', function() {
                                BusquedaLugares()
                        });
                        google.maps.event.addListener(map, "click", function(event) {
                        UpdateMapaClick(event.latLng.lat(), event.latLng.lng());
                });
                
                        UpdateMapaClick(<?php echo $coordenadas_geo_dom; ?>);
        }


        function createMarker_map(markerOptions) {
                clearOverlays();
                var marker = new google.maps.Marker(markerOptions);
                markers_map.push(marker);
                lat_longs_map.push(marker.getPosition());
                return marker;
        }

        function clearOverlays() {
          for (var i = 0; i < markers_map.length; i++ ) {
                markers_map[i].setMap(null);
          }
          markers_map.length = 0;
        }

        function placesCallback(results, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                        for (var i = 0; i < results.length; i++) {

                                var place = results[i];

                                var placeLoc = place.geometry.location;
                                var placePosition = new google.maps.LatLng(placeLoc.lat(), placeLoc.lng());
                                var markerOptions = {
                                        map: map,
                                        position: placePosition
                                };
                                var marker = createMarker_map(markerOptions);
                                marker.set("content", place.name);
                                google.maps.event.addListener(marker, "click", function() {
                                        iw_map.setContent(this.get("content"));
                                        iw_map.open(map, this);
                                });

                                lat_longs_map.push(placePosition);

                        }

                }
        }

        google.maps.event.addDomListener(window, "load", initialize_map);

        //]]>

</script>

<div>

    <input id="pac-input" class="controls" type="text" placeholder="Escribe la ubicación aquí..." value="La Paz, Bolivia" autocomplete="off" onfocus="this.value='';">
    <input id="mis_coordenadas_mapa" name="mis_coordenadas_mapa" type="hidden">
    <div id="wrapper">

        <div id="map_canvas" style="width: 100%; height: 345px; position: relative; overflow: hidden;"><div style="height: 100%; width: 100%; position: absolute; top: 0px; left: 0px; background-color: rgb(229, 227, 223);"><div style="overflow: hidden;"></div><div class="gm-style" style="position: absolute; z-index: 0; left: 0px; top: 0px; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px;"><div tabindex="0" style="position: absolute; z-index: 0; left: 0px; top: 0px; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; cursor: url(&quot;https://maps.gstatic.com/mapfiles/openhand_8_8.cur&quot;), default; touch-action: pan-x pan-y;"><div style="z-index: 1; position: absolute; left: 50%; top: 50%; width: 100%; transform: translate(0px, 0px);"><div style="position: absolute; left: 0px; top: 0px; z-index: 100; width: 100%;"><div style="position: absolute; left: 0px; top: 0px; z-index: 0;"><div style="position: absolute; z-index: 985; transform: matrix(1, 0, 0, 1, -117, -229);"><div style="position: absolute; left: 0px; top: 0px; width: 256px; height: 256px;"><div style="width: 256px; height: 256px;"></div></div></div></div></div><div style="position: absolute; left: 0px; top: 0px; z-index: 101; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 102; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 103; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 0;"></div></div><div class="gm-style-pbc" style="z-index: 2; position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px; opacity: 0;"><p class="gm-style-pbt"></p></div><div style="z-index: 3; position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px; touch-action: pan-x pan-y;"><div style="z-index: 4; position: absolute; left: 50%; top: 50%; width: 100%; transform: translate(0px, 0px);"><div style="position: absolute; left: 0px; top: 0px; z-index: 104; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 105; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 106; width: 100%;"></div><div style="position: absolute; left: 0px; top: 0px; z-index: 107; width: 100%;"></div></div></div></div><iframe aria-hidden="true" frameborder="0" src="about:blank" style="z-index: -1; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; border: none;"></iframe></div></div></div>

        <div class="AvisoMapa">Clic en la ubicación requerida</div>

    </div>

</div>