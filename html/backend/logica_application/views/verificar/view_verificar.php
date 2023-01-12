<?php
/**
 * @file 
 * Pagina Principal de Llamada a Librerias 
 * @author JRAD
 * @date Jun, 2017
 * @copyright OWNER
 */

?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8" />
        <title>
            :: <?php echo $this->lang->line('NombreSistema_corto'); ?> ::
        </title>
        <link rel="stylesheet" type="text/css"
              href="html_public/css/estilo_general.css?ver=6" />
        <link rel="stylesheet" type="text/css"
              href="html_public/css/tooltip.css" />
        <link rel="stylesheet" type="text/css"
              href="html_public/css/font-awesome.css" />
    </head>
    
    <body class="PaginaContenidoGeneral">
            
        <div id="divVistaMenuPantalla" align="center">

            <br /><br /><br /><br />
            
            <div id="divCargarFormulario" class="TamanoContenidoGeneral">

                <div class="PreguntaConfirmar" style="font-size: 22px !important;">
                                        
                    <br /><br />
                    
                    <?php echo $texto; ?>
                    
                </div>
                
                <br /><br />
                    
                <div class="Botones2Opciones" style="float: none;">
                    <a onclick="javascript:location.href='https://www.initium-app.com/atc/'" class="BotonMinimalista"> Volver al Sitio </a>
                </div>
                
            </div>

        </div>        
        
    </body>

</html>