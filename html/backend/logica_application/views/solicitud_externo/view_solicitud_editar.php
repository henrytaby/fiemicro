<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8" />
        <title>
            :: <?php echo $this->lang->line('NombreSistema_corto'); ?> ::
        </title>
        <link rel="stylesheet" type="text/css"
              href="../../html_public/css/estilo_general.css?ver=6" />
        <link rel="stylesheet" type="text/css"
              href="../../html_public/css/tooltip.css" />
        <link rel="stylesheet" type="text/css"
              href="../../html_public/css/font-awesome.css" />
        <link rel="stylesheet" type="text/css"
        <link rel="stylesheet" type="text/css"
              href="../../html_public/js/lib/themes/base/jquery.ui.all.css" />
        <script type="text/javascript"
        src="../../html_public/js/lib/jquery-1.10.2.min.js"></script>
        <script type="text/javascript"
        src="../../html_public/js/lib/jquery.validate.min.js"></script>
        <script type="text/javascript"
        src="../../html_public/js/lib/ui/jquery-ui.custom.min.js"></script>
        <script type="text/javascript"
        src="../../html_public/js/lib/ui/jquery.ui.datepicker.min.js"></script>
        <script type="text/javascript"
        src="../../html_public/js/lib/ui/jquery.ui.datepicker-es.js"></script>
        
        <script type="text/javascript"
        src="../../html_public/js/js_pagina_logica.js"></script>

        <script type="text/javascript"
                src="../../html_public/js/lib/datatable/js/jquery.dataTables.min.js"></script>

        <script type="text/javascript"
                src="../../html_public/js/lib/datatable/js/jquery.dataTables.rowsGroup.js"></script>



        <script type="text/javascript"
                src="../../html_public/js/lib/charts/chart.bundle.js"></script>

        <script type="text/javascript"
        src="../../html_public/js/lib/transformaciones/numero_a_palabras.js"></script>

        <link rel="stylesheet" type="text/css"
              href="../../html_public/js/lib/datatable/css/jquery.dataTables.css" />

        <script type="text/javascript" src="../../html_public/js/lib/autocomplete/jquery-ui.js"></script>
        <script type="text/javascript" src="../../html_public/js/lib/autocomplete/autocomplete.js"></script>
        <style type="text/css" src="../../html_public/js/lib/autocomplete/jquery-ui.js"></style>
        <link rel="stylesheet" href="../../html_public/js/lib/autocomplete/jquery-ui.css"></link>
        
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui, shrink-to-fit=no">
        <script type="text/javascript"
        src="../../html_public/js/lib/ui/jquery.ui.timepicker.min.js"></script>
        <script type="text/javascript"
        src="../../html_public/js/lib/ui/jquery.ui.timepicker-es.js"></script>
        
        <!-- favicons -->
        
        <link rel="apple-touch-icon" sizes="180x180" href="../../html_public/imagenes/favicons/apple-touch-icon.png?v=qA3LBrJxN9">
        <link rel="icon" type="image/png" sizes="32x32" href="../../html_public/imagenes/favicons/favicon-32x32.png?v=qA3LBrJxN9">
        <link rel="icon" type="image/png" sizes="16x16" href="../../html_public/imagenes/favicons/favicon-16x16.png?v=qA3LBrJxN9">        
        <link rel="icon" href="../../html_public/imagenes/favicons/android-chrome-192x192.png?v=qA3LBrJxN9" sizes="192x192" />
        <link rel="icon" href="../../html_public/imagenes/favicons/android-chrome-512x512.png?v=qA3LBrJxN9" sizes="512x512" />            
        <link rel="manifest" href="../../html_public/imagenes/favicons/manifest.json?v=qA3LBrJxN9">
        <link rel="mask-icon" href="../../html_public/imagenes/favicons/safari-pinned-tab.svg?v=qA3LBrJxN9" color="#e27801">
        <link rel="shortcut icon" href="../../html_public/imagenes/favicons/favicon.ico?v=qA3LBrJxN9">
        <meta name="apple-mobile-web-app-title" content="SENAF Web">
        <meta name="application-name" content="SENAF Web">
        <meta name="msapplication-config" content="html_public/imagenes/favicons/browserconfig.xml?v=qA3LBrJxN9">
        <meta name="theme-color" content="#eeeeee">
        
        <!-- favicons -->
            
        <script type="text/javascript">
        
            $(document).ajaxStart(function() {
                $("#PaginaPrincipal_Cargando").fadeIn("fast");
            });
            $(document).ajaxStop(function() {
                $("#PaginaPrincipal_Cargando").fadeOut("fast");
            });

        </script>
		
		<script>
			$(document).ready(function(){
				if(window.console || "console" in window) {
					console.log("%c ¡PRECAUCIÓN!", "color:#3c2c70; font-size:40px;");
					console.log("%c <?php echo $this->lang->line('Self-XSS'); ?>", "color:#003087; font-size:12px;");
				}
			});
		</script>
                    
                <script>
                    // Funcion para evitar ir atrás en el navegador
                    history.pushState(null, null, document.URL);
                    window.addEventListener('popstate', function () {
                        history.pushState(null, null, document.URL);
                    });

                </script>
                
    </head>

<body class="PaginaContenidoGeneralExterno" style="left: 0;top:0;position:absolute;right:0;">

    <div id="divFondoElementoFlotante" style="display:none" class="ModalFondoSemiTransparente"></div>
    <div id="divContenidoElementoFlotante" style="display:none;" class="ElementoFlotanteExterno">            
        <span id="btnModalCerrarVentana" style="display: block; position: absolute; right: 5px; top: 10px"> 
            <a onclick="Elementos_General_MostrarElementoFlotante(false);" style="margin-left:0mm;">  
                <img src="../../html_public/imagenes/cerrar_modal.png" alt="cerrar" /></a>
        </span>
        <div id="divElementoFlotante" style="width:90%;" class="Centrado"></div>                
    </div>                           
    <div id="divCargarRespuestaScript" style="display:none" ></div>
        
<script type="text/javascript">
    
    function Ajax_CargarAccion_Guardar() {
       
        var qr_nombre = $('#qr_nombre').val();
        var qr_empresa = $('#qr_empresa').val();
        var qr_ciudad = $('#qr_ciudad').val();
        var qr_correo = $('#qr_correo').val();
        var imagen = $('#imagen').val();
        
        var strParametros = "&tipo_accion=0" + "&qr_nombre=" + qr_nombre + "&qr_empresa=" + qr_empresa + "&qr_ciudad=" + qr_ciudad + "&qr_correo=" + qr_correo + "&imagen=" + imagen;
        Ajax_CargadoGeneralPagina('../../Qr/Externo/Guardar', 'divVistaMenuPantalla', "divErrorListaResultado", '', strParametros);
    }

</script>

    <div id="divVistaMenuPantalla" align="center">

        <br /><br />
        
        <div id="divCargarFormulario" class="TamanoContenidoGeneralExterno">
            
            <br /><br />
            
                <div class="FormularioSubtitulo"> Puedes Comprar tu Entrada Ahora ! <i class="fa fa-cart-arrow-down"></i> </div>
                <div class="FormularioSubtituloComentarioNormal " style="font-size: 14px;"> Para poder comprar su entrada, por favor complete el siguiente formulario te enviaremos tu comprobante electrónico y tu factura a tu correo electrónico. </div>

            <div style="clear: both"></div>

            <br />

            <form id="FormularioRegistroLista" method="post">

                <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

                <input type="hidden" name="redireccionar" value="" />

        <div class="FormularioSubtituloMediano">
            
            NOMBRE DEL EVENTO: Feria del Libro Gestión 2018
            <br /><br />
            COSTO DEL EVENTO: Bs. 20
            <br /><br />
        </div>

        <div style="clear: both"></div>
        
        <br /><br />
        
            <table class="tablaresultados Mayuscula" style="width: 100% !important; background-color: transparent !important; border: 0px !important;" border="0">

            <?php $strClase = ""; ?>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    Ingresa tu Nombre Completo
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["qr_nombre"]; ?>
                </td>
            </tr>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">
                <td style="width: 30%; font-weight: bold;">
                    Empresa/Colegio/Universidad
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["qr_empresa"]; ?>
                </td>
            </tr>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">
                <td style="width: 30%; font-weight: bold;">
                    En que ciudad te encuentras
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["qr_ciudad"]; ?>
                </td>
            </tr>
                
            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">
                <td style="width: 30%; font-weight: bold;">
                    Tu Correo Electrónico
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["qr_correo"]; ?>
                </td>
            </tr>
            
            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('externo_captcha'); ?>
                </td>

                <td valign="middle" style="width: 70%;">
                    
                    <span id="imgCaptchaLogin"><?php echo $imgTagHtml; ?></span>
                                
                    <span class="EnlaceSimple" onclick="recargar_captcha_externo('imgCaptchaLogin');" style="cursor: pointer; font-size: 12px; font-weight: bold; vertical-align: top; line-height: 35px;">                                                                                    
                        <i class="fa fa-refresh" aria-hidden="true" style="vertical-align: top !important; line-height: 35px !important;"></i> Recargar Imagen
                    </span>

                    <br />

                    <?php echo $arrCajasHTML["imagen"]; ?>
                    
                </td>

            </tr>
                
        </table>

            </form>

            <br /><br /><br />

            <div id="divErrorListaResultado" class="mensajeBD"> </div>
            
            <div class="">
                <a onclick="Ajax_CargarAccion_Guardar()" class="BotonMinimalista"> Registrarme y Proceder con el Pago </a>
            </div>

            <div style="clear: both"></div>

        </div>
    </div>
    
<div style="display: none; color: #f5811e; top: 150px;" id="PaginaPrincipal_Cargando">
   Cargando... espere por favor...<br/>
    <img src="../../html_public/imagenes/Cargando.gif" alt="Cargando" />
</div>

</body>
</html>