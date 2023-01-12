<?php
/**
 * @file 
 * Pagina Principal de contenido Externo
 * @author JRAD
 * @date Feb, 2019
 * @copyright OWNER
 */

?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=11" />
        <title>
            :: <?php echo $this->lang->line('NombreSistema_corto'); ?> ::
        </title>
        <link rel="stylesheet" type="text/css"
              href="html_public/css/estilo_general.css?ver=6" />
        <link rel="stylesheet" type="text/css"
              href="html_public/css/tooltip.css" />
        <link rel="stylesheet" type="text/css"
              href="html_public/css/font-awesome.css" />
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui, shrink-to-fit=no" />
        
        <!-- favicons -->
        
        <link rel="apple-touch-icon" sizes="180x180" href="html_public/imagenes/favicons/apple-touch-icon.png?v=qA3LBrJxN9">
        <link rel="icon" type="image/png" sizes="32x32" href="html_public/imagenes/favicons/favicon-32x32.png?v=qA3LBrJxN9">
        <link rel="icon" type="image/png" sizes="16x16" href="html_public/imagenes/favicons/favicon-16x16.png?v=qA3LBrJxN9">        
        <link rel="icon" href="html_public/imagenes/favicons/android-chrome-192x192.png?v=qA3LBrJxN9" sizes="192x192" />
        <link rel="icon" href="html_public/imagenes/favicons/android-chrome-512x512.png?v=qA3LBrJxN9" sizes="512x512" />            
        <link rel="manifest" href="html_public/imagenes/favicons/manifest.json?v=qA3LBrJxN9">
        <link rel="mask-icon" href="html_public/imagenes/favicons/safari-pinned-tab.svg?v=qA3LBrJxN9" color="#e27801">
        <link rel="shortcut icon" href="html_public/imagenes/favicons/favicon.ico?v=qA3LBrJxN9">
        <meta name="apple-mobile-web-app-title" content="SENAF Web">
        <meta name="application-name" content="SENAF Web">
        <meta name="msapplication-config" content="html_public/imagenes/favicons/browserconfig.xml?v=qA3LBrJxN9">
        <meta name="theme-color" content="#eeeeee">
        
        <script type="text/javascript" src="html_public/js/js_pagina_logica.js"></script>
            
         <script type="text/javascript" src="html_public/js/lib/jquery-1.10.2.min.js"></script>
            
            <script>
                $(document).ready(function(){
                        if(window.console || "console" in window) {
                                console.log("%c ¡PRECAUCIÓN!", "color:#3c2c70; font-size:40px;");
                                console.log("%c <?php echo $this->lang->line('Self-XSS'); ?>", "color:#003087; font-size:12px;");
                        }
                });
                
                function show_resource(){
                    $('#pass_resource_view').hide();
                    $('#pass_resource').fadeIn();
                }
                
            </script>
            
        <!-- favicons -->
        
    </head>
    
    <body class="PaginaContenidoGeneral">
            
        <div id="divVistaMenuPantalla" align="center">

            <br /><br /><br /><br />
            
            <div id="divCargarFormulario" class="TamanoContenidoGeneral">

                <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                <div class="FormularioSubtitulo"> Bienvenido(a) <?php echo $arrResultado[0]['afiliador_responsable_nombre']; ?> </div>
                <div class="FormularioSubtituloComentarioNormal "> Información de Integración Afiliación por Terceros. Por favor siga las instrucciones mostradas a continuación. </div>
                
                <div style="clear: both"></div>
                
                <br /><br />
                
                <table class="tblListas Centrado" style="width: 80%;" border="0">
                
                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                    
                    <tr class="FilaCabecera">
                        <th style="width:100%;" colspan="2">
                            <strong>A continuación te brindamos información para que puedas integrar la Afiliación por Terceros:</strong>
                        </th>
                    </tr>
                    
                    <tr class="FilaBlanca">
                        <td style="width:30%; text-align: justify;">
                            <strong><i class="fa fa-lock" aria-hidden="true"></i> Responsabilidad de Uso: </strong>
                        </td>

                        <td style="width:70%; text-align: justify;">
                            El uso de la siguiente información asi como credenciales proporcionadas, es de expresa responsabilidad de la Empresa Afiliadora, por lo que cualquier uso incorrecto de la herramienta será pasible a las acciones legales pertinentes.
                        </td>
                    </tr>
                    
                    <tr class="FilaBlanca">
                        <td style="width:30%; text-align: justify;">
                            <strong><i class="fa fa-flag" aria-hidden="true"></i> Fuente de Afiliación como: </strong>
                        </td>

                        <td style="width:70%; text-align: justify;">
                            <?php echo $arrResultado[0]['afiliador_nombre']; ?>
                        </td>
                    </tr>
                    
                    <tr class="FilaBlanca">
                        <td style="width:30%; text-align: justify;">
                            <strong><i class="fa fa-key" aria-hidden="true"></i> Llave de Afiliación: </strong>
                        </td>

                        <td style="width:70%; text-align: justify; font-style: italic;">
                            <span id="text_key" style="color: #008b9a;"><?php echo $arrResultado[0]['afiliador_key']; ?></span>
                        </td>
                    </tr>
                    
                    <tr class="FilaBlanca">
                        <td style="width:30%; text-align: justify;">
                            <strong><i class="fa fa-user-circle-o" aria-hidden="true"></i> Credenciales de Acceso: </strong>
                        </td>

                        <td style="width:70%; text-align: justify;">
                            Puedes utilizar las credenciales (usuario y contraseña) del usuario habilitado con el perfil "Afiliador Tercero". Si no tienes habilitado un usuario con el perfil requerido, puedes comunicarte con el administrador del Sistema a los números de ATC Red Enlace.
                        </td>
                    </tr>

                    <tr class="FilaBlanca">
                        <td style="width:30%; text-align: justify;">
                            <strong><i class="fa fa-book" aria-hidden="true"></i> Descargar el Landing-Page Demo y Documentación de los Web Services de Integración: </strong>
                        </td>

                        <td style="width:70%; text-align: justify;">
                            <strong><a href="#" onclick="window.open('html_public/manual/ATCresources_afiliacion_terceros.zip');return false;"> <i class="fa fa-download" aria-hidden="true"></i> Clic aquí para descargar </a></strong>
                            
                            <br /><br />
                            
                            <span style="font-size: 0.80em;">
                            
                                <strong>SHA-256: </strong> <?php echo hash_file('sha256', 'html_public/manual/ATCresources_afiliacion_terceros.zip'); ?>
                                <br />
                                <strong>Contraseña Compreso: </strong>
                                
                                <span style="color: #008b9a; cursor: pointer;" id="pass_resource_view" onclick="show_resource();"><i class="fa fa-eye" aria-hidden="true"></i> Mostrar Contraseña</span>
                                
                                <span id="pass_resource" style="display: none; color: #505050; font-style: italic;">R32ENL4C3_resources_afiliacion_terceros</span>
                            
                            </span>
                            
                        </td>
                    </tr>
                    
                    <tr class="FilaGris">
                        <td style="width:30%; text-align: justify;">
                            <i><strong><i class="fa fa-question-circle" aria-hidden="true"></i> ¿Tienes Preguntas? </strong></i>
                        </td>

                        <td style="width:70%; text-align: justify;">
                            <i>Podemos ayudarte, por favor comunícate con nosotros - <a href="https://www.redenlace.com.bo/index_contact_center.php" target="_blank"><strong>ATC Red Enlace</strong></a>.</i>
                        </td>
                    </tr>
                    
                </table>
                
            </div>

        </div>        
        
    </body>

</html>