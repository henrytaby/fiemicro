<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui, shrink-to-fit=no" />
        <title>
            :: <?php echo $this->lang->line('NombreSistema_corto'); ?> ::
        </title>

        <!-- favicons -->
        
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $this->config->base_url(); ?>html_public/imagenes/favicons/apple-touch-icon.png" />
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $this->config->base_url(); ?>html_public/imagenes/favicons/favicon-32x32.png" />
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $this->config->base_url(); ?>html_public/imagenes/favicons/favicon-16x16.png" />
        <link rel="mask-icon" href="<?php echo $this->config->base_url(); ?>html_public/imagenes/favicons/safari-pinned-tab.svg" color="#006699" />
        <meta name="apple-mobile-web-app-title" content="Initium App" />
        <meta name="application-name" content="Initium App" />
        <meta name="theme-color" content="#ffffff" />
        <link rel="shortcut icon" href="<?php echo $this->config->base_url(); ?>html_public/imagenes/favicons/favicon.ico" />
        
        <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>html_public/js/lib/jquery-1.10.2.min.js"></script>
        
        <!-- favicons -->
        
        <style>
            
            .btn-primary {
                background: #006699;
                background: -moz-linear-gradient(top, #006699 0%, #00577f 100%);
                background: -webkit-linear-gradient(top, #006699 0%, #00577f 100%);
                background: linear-gradient(to bottom, #006699 0%, #00577f 100%);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00577f', GradientType=0);
                color: #efefef;
                font-weight: bold;
                text-align: center;
                cursor: pointer;
                padding: 10px 5px;
                text-transform: none;
                display: inline-block;
                width: 50%;
                border-radius: 20px;
                box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2),
                0 2px 5px 0 rgba(0, 0, 0, 0.2);
                border: 1px solid transparent;
                padding: 5px 0px !important;
                width: 170px !important;
                height: 30px;
            }
            
        </style>
        
    </head>

    <body style="left: 0;top:0;position:absolute;right:0;">
        
    <script type="text/javascript">

        // Global
        var cont_reintentos = 0;

        function send_form()
        {
            document.getElementById("formID").submit();
            
            setTimeout(function () {
                
                document.getElementById("msg_loading").innerHTML = "Elementos de referencia y formulario generado. La ventana se cerrará automáticamente.<br />";
                aux_close();
                
            }, 10000);
        }

        // Función para el envío
        function aux_send()
        {
            // Se autoincrementa el contador para tener un control de escape
            cont_reintentos++;
            if(cont_reintentos > 60)
            {
                // Si se alcanza la cantidad de intentos máximo, se debe indicar error y cerrar
                send_form();
            }
            else
            {
                // Se verifica que todos los campos hidden tengan valor (imagen base64) cada 0.5 segundos
                setTimeout(function () {

                    if(
                        document.getElementById("sol_geolocalizacion").value == '' || 
                        document.getElementById("sol_geolocalizacion_dom").value == '' || 
                        document.getElementById("sol_con_geolocalizacion").value == ''
                      )
                    {
                        // Si todos no tienen valor vuelve a intentar (hasta el máximo de intentos
                        aux_send();
                    }
                    else
                    {
                        // Si todos tienen valor, se envía el formulario y se muestra mensaje que se cerrará automáticamente (mostrar: si ya descargó el PDF puede cerrar la pestaña ahora)
                        send_form();
                    }

                }, 500);
            }
        }

        // Función que muestra error y finaliza
        function aux_error()
        {
            document.getElementById("msg_loading").innerHTML = "No fue posible generar el formulario. <br /><br /> <button type=button class=btn-primary onclick=window.close();>Cerrar ventana</button>";
            aux_finalizar();
        }

        // Función con tiempo adicional de espera para posteriormente finalizar
        function aux_close()
        {
            setTimeout(function () {
                aux_finalizar()
            }, 30000);
            
            setTimeout(function () {
                document.getElementById("msg_loading").innerHTML = "Elementos de referencia y formulario generado. La ventana se cerrará automáticamente.<br /><br /><button type=button class=btn-primary onclick=window.close();>Cerrar ventana</button>";
            }, 15000);
        }

        // Función para cerra la pestaña del explorador
        function aux_finalizar()
        {
            window.close();
        }
    
    </script>

    <form method="post" enctype="multipart/form-data" id="formID" accept-charset="utf-8" action="<?php echo $this->config->base_url() . 'Registros/SolCred/Formulario'; ?>">
        <input type="hidden" name="ota_key" id="ota_key" value="<?php echo $ota_key; ?>"/>
        <input type="hidden" name="sol_geolocalizacion" id="sol_geolocalizacion" value="<?php echo $sol_geolocalizacion->resultado; ?>"/>
        <input type="hidden" name="sol_geolocalizacion_dom" id="sol_geolocalizacion_dom" value="<?php echo $sol_geolocalizacion_dom->resultado; ?>"/>
        <input type="hidden" name="sol_con_geolocalizacion" id="sol_con_geolocalizacion" value="<?php echo $sol_con_geolocalizacion->resultado; ?>"/>
    </form>

<?php echo $mensaje; ?>
    
    <div align="center">
        <div style="font-size: 12px; font-family: tahoma, arial; padding: 20px 5px; text-align: center; box-shadow: 0 4px 6px 0 rgb(0 0 0 / 20%), 0 4px 10px 0 rgb(0 0 0 / 19%); margin-top: 10px; background-color: #ffffff; width: 90%;">

            <div id="msg_loading">
                Generando elementos de referencia<?php echo ($this->mfunciones_microcreditos->CheckIsMobile() ? '. Al completar comenzará la descarga del PDF automáticamente' : ''); ?>, por favor espere...
                <br />
                <img style="height: 50px;" border="0" src="<?php echo $this->config->base_url(); ?>html_public/imagenes/Cargando.gif" alt="Cargando" />
            </div>

        </div>
    </div>
    
    <div id="contenedor_mapas" style="margin-top: 4000px;"> </div>
    
    <link rel="stylesheet" href="<?php echo $this->config->base_url(); ?>html_public/js/geo_img/leaflet.css" />
    <script src="<?php echo $this->config->base_url(); ?>html_public/js/geo_img/leaflet.js"></script>
    <script src="<?php echo $this->config->base_url(); ?>html_public/js/geo_img/dom-to-image.min.js"></script>
    
    <script type="text/javascript">
    
        const createMapImage = async (lat, long, id_map) => {
            // Establecer dimension tanto para el mapa como para la imagen
            const width = 700;
            const height = 400;

            const mapElement = document.createElement("div");
            mapElement.setAttribute("id", "map_" + id_map);
            mapElement.style.width = `${width}px`;
            mapElement.style.height = `${height}px`;
            document.getElementById("contenedor_mapas").appendChild(mapElement);

            const map = L.map(mapElement, {
                attributionControl: false,
                zoomControl: false,
                fadeAnimation: false,
                zoomAnimation: false
            }).setView([lat, long], 17);

            const tileLayer = L.tileLayer(
                "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
            ).addTo(map);

            L.marker([lat, long]).addTo(map);

            await new Promise((resolve) => tileLayer.on("load", () => resolve()));
        };

        <?php
        
            $aux_cont_tiempo = 1;
        
            if($sol_geolocalizacion->lat != 0)
            {
                echo 'createMapImage(' . $sol_geolocalizacion->lat . ', ' . $sol_geolocalizacion->long . ', "sol_geolocalizacion");';
                $aux_cont_tiempo++;
            }
            
            if($sol_geolocalizacion_dom->lat != 0)
            {
                echo 'createMapImage(' . $sol_geolocalizacion_dom->lat . ', ' . $sol_geolocalizacion_dom->long . ', "sol_geolocalizacion_dom");';
                $aux_cont_tiempo++;
            }
            
            if($sol_con_geolocalizacion->lat != 0)
            {
                echo 'createMapImage(' . $sol_con_geolocalizacion->lat . ', ' . $sol_con_geolocalizacion->long . ', "sol_con_geolocalizacion");';
                $aux_cont_tiempo++;
            }
        ?>


        function MapToImg(id)
        {
            var node = document.getElementById("map_"+id);

            const options = {
                // Establecer dimension tanto para el mapa como para la imagen
                width: 700,
                height: 400
            };

            domtoimage.toPng(node)
            .then(function (dataUrl) {
                document.getElementById(id).value = dataUrl;
            });

            domtoimage.toPng(node, options)
            .then(function (dataUrl) {
                
                $.ajax({
                    url: '../AuxMapSol',
                    type: 'post',
                    async: false,
                    data: {
                        ota_key:'<?php echo $ota_key; ?>', aux_campo:id ,base64:dataUrl
                    },
                    dataType: 'json'
                });
                
                document.getElementById(id).value = dataUrl;
                document.getElementById("contenedor_mapas").removeChild(node);
                
            });
        }

        function OrquestadorMapas()
        {
            setTimeout(function () {
                
            <?php
                
                if($sol_geolocalizacion->lat != 0)
                {
                    echo 'MapToImg("sol_geolocalizacion");';
                }

                if($sol_geolocalizacion_dom->lat != 0)
                {
                    echo 'MapToImg("sol_geolocalizacion_dom");';
                }

                if($sol_con_geolocalizacion->lat != 0)
                {
                    echo 'MapToImg("sol_con_geolocalizacion");';
                }
            ?>  
                aux_send();
                
            }, <?php echo $aux_cont_tiempo*1500; ?>);
        }
    
        OrquestadorMapas();
    
    </script>
    
    </body>
    
</html>