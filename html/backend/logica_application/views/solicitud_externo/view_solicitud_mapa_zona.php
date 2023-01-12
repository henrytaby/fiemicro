<html>
<head>
    <script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        function ActualizarGeoSolicitud(newLat, newLng)
        {
            $.post(
                    "../../Externo/Zona/Guardar",
                    { 'newLat': newLat, 'newLng': newLng }
            )
            .done(function(data) {
                alert(" :: Ubicación Guardada Correctamente :: ");
                getReverseGeocodingData(newLat, newLng);
            });        
        }
        
        function ActualizarZonaEjecutivoAuxliar(newLat, newLng)
        {
            var cnfrm = confirm('Marcar posición manualmente, se actualizará el mapa ¿Desea continuar?');
            if(cnfrm != true)
            {
                return false;
            }
            else
            {
                $.post(
                        "../../Externo/Zona/Guardar",
                        { 'newLat': newLat, 'newLng': newLng }
                )
                .done(function(data) {
                    getReverseGeocodingData(newLat, newLng);
                    parent.refresh_iframe('mapa_visor');
                });
            }
        }
        
        // Try W3C Geolocation (Preferred)        
        function actual(){
        
            if(navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                        map.setCenter(new google.maps.LatLng(position.coords.latitude,position.coords.longitude));
                }, function() { alert("No se puede localizar tu ubicación actual. La Geolocalización falló."); });
            // Browser doesn\'t support Geolocation
            }else{
                    alert('Your browser does not support geolocation.');
            }
        }
        
        function getReverseGeocodingData(lat, lng) {
	
            var latlng = new google.maps.LatLng(lat, lng);
            // This is making the Geocode request
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                if (status !== google.maps.GeocoderStatus.OK) {

                }
                // This is checking to see if the Geoeode Status is OK before proceeding
                if (status == google.maps.GeocoderStatus.OK) {
                    var address = (results[0].formatted_address);

                    window.location.hash = '#direccion_actual';
                    document.getElementById("direccion_actual").innerHTML = address;                    
                }
            });
        }
        
    </script>
    <?php echo $map['js']; ?>
</head>
<body>
    
    <div align="center">    
        <div id="direccion_actual" style="text-align: center; color: #ffffff; font-size: 11px; padding: 5px; border: 3px solid #ffffff; background-color: #006699; border-radius: 20px; font-weight: bold; width: 80%; font-family: 'Open Sans', Arial, sans-serif;"> <i>Puede copiar la dirección...</i> </div>
    </div>
    
    <?php echo $map['html']; ?>

</body>
</html>