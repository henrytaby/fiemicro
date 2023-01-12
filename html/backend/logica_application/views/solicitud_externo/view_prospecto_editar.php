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
            
    function Ajax_CargarAccion_MapaSolicitud(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('../../Solicitud/Externo/Zona', 'divElementoFlotante', "divErrorListaResultado", '', strParametros);
    }
    
    function Ajax_CargarAccion_Guardar() {
       
        var solicitud_nombre_persona = $('#solicitud_nombre_persona').val();
        var solicitud_nombre_empresa = $('#solicitud_nombre_empresa').val();
        var catalogo_ciudad = $('#catalogo_ciudad').val();
        var solicitud_telefono = $('#solicitud_telefono').val();
        var solicitud_email = $('#solicitud_email').val();
        var solicitud_direccion_literal = $('#solicitud_direccion_literal').val();
        var catalogo_rubro = $('#catalogo_rubro').val();
        var imagen = $('#imagen').val();
        
        var arrServicios = [];
        
        $('#FormularioRegistroLista').find("input[type=checkbox]:checked").each(function () {
            
            arrServicios.push($(this).val());
        });
        
        var servicio_list = JSON.stringify(arrServicios);
        
        var strParametros = "&tipo_accion=0" + "&solicitud_nombre_persona=" + solicitud_nombre_persona + "&solicitud_nombre_empresa=" + solicitud_nombre_empresa + "&catalogo_ciudad=" + catalogo_ciudad + "&solicitud_telefono=" + solicitud_telefono + "&solicitud_email=" + solicitud_email + "&solicitud_direccion_literal=" + solicitud_direccion_literal + "&catalogo_rubro=" + catalogo_rubro + "&servicio_list=" + servicio_list + "&imagen=" + imagen;
        Ajax_CargadoGeneralPagina('../../Externo/Prospecto/Guardar', 'divVistaMenuPantalla', "divErrorListaResultado", '', strParametros);
    }

</script>

    <div id="divVistaMenuPantalla" align="center">

        <div id="divCargarFormulario" class="TamanoContenidoGeneralExterno">
            
                <div class="FormularioSubtitulo"> <?php echo $this->lang->line('externo_prospecto_titulo'); ?></div>
                <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('externo_prospecto_subtitulo'); ?></div>

            <div style="clear: both"></div>

            <br />

            <form id="FormularioRegistroLista" method="post">

                <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

                <input type="hidden" name="redireccionar" value="" />

                <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0])){ echo $arrRespuesta[0]["solicitud_id"]; } ?>" />

                <input type="hidden" name="tipo_accion" value="<?php echo $tipo_accion; ?>" />
                
            <table class="tablaresultados Mayuscula" style="width: 100% !important; background-color: transparent !important; border: 0px !important;" border="0">

            <?php $strClase = ""; ?>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_nombre_persona'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["solicitud_nombre_persona"]; ?>
                </td>
            </tr>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">
                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_nombre_empresa'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["solicitud_nombre_empresa"]; ?>
                </td>
            </tr>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">
                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_ciudad'); ?>
                </td>

                <td style="width: 70%;">
                    
                    <?php                                            
                        if(count($arrCiudad[0]) > 0)
                        {
                            $valor_parent = '-1';
                            if($tipo_accion == 1)
                            {
                                $valor_parent = $arrRespuesta[0]['solicitud_ciudad_codigo'];
                            }
                            
                            echo html_select('catalogo_ciudad', $arrCiudad, 'catalogo_codigo', 'catalogo_descripcion', '', $valor_parent);
                        }
                        else
                        {
                            echo $this->lang->line('TablaNoRegistros');
                        }
                    ?>
                    
                </td>
            </tr>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_telefono'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["solicitud_telefono"]; ?>
                </td>

            </tr>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_email'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["solicitud_email"]; ?>
                </td>

            </tr>
            
            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_direccion_geo'); ?> <i class="fa fa-map-marker" aria-hidden="true"></i>
                </td>

                <td style="width: 70%;">                    
                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_MapaSolicitud('<?php echo $_SESSION['coordenadas_solicitud']; ?>')">
                        <strong><i class="fa fa-street-view" aria-hidden="true"></i><?php echo $this->lang->line('solicitud_direccion_geo_des'); ?>
                    </span>
                    
                    <br />
                </td>

            </tr>
            
            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_direccion_literal'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["solicitud_direccion_literal"]; ?>
                </td>

            </tr>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_rubro'); ?>
                </td>

                <td style="width: 70%;">
                    
                    <?php                                            
                        if(count($arrRubro[0]) > 0)
                        {
                            $valor_parent = '-1';
                            if($tipo_accion == 1)
                            {
                                $valor_parent = $arrRespuesta[0]['solicitud_rubro_codigo'];
                            }
                            
                            echo html_select('catalogo_rubro', $arrRubro, 'catalogo_codigo', 'catalogo_descripcion', '', $valor_parent);
                        }
                        else
                        {
                            echo $this->lang->line('TablaNoRegistros');
                        }
                    ?>
                    
                </td>

            </tr>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_servicios'); ?>
                </td>

                <td style="width: 70%;">

                    <br />
                        
                    <?php

                        if(isset($arrServicios[0]))
                        {
                            $i = 0;
                            $checked = '';

                            foreach ($arrServicios as $key => $value) 
                            {
                                $checked = '';
                                if($value["servicio_asignado"])
                                {
                                    $checked = 'checked="checked"';
                                }

                                echo '<div class="divOpciones">';
                                echo '<input id="servicio' . $i , '" type="checkbox" name="servicio_list[]" '. $checked .' value="' . $value["servicio_id"] . '">';
                                echo '<label for="servicio' . $i , '"><span></span>' . $value["servicio_detalle"] . '</label>';
                                echo '</div>';

                                $i++;
                            }
                        }

                    ?>
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
                <a onclick="Ajax_CargarAccion_Guardar()" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar_enviar'); ?> </a>
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