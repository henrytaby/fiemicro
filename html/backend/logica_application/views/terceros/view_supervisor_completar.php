<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Afiliador/Completar/Guardar',
            'divVistaMenuPantalla', 'divErrorListaResultado');
            
    $("#divCargarFormulario").show();    
    $("#confirmacion").hide();

    function MostrarConfirmación()
    {
        $("#divCargarFormulario").hide();
        $("#confirmacion").fadeIn(500);
    }
    
    function OcultarConfirmación()
    {
        $("#divCargarFormulario").fadeIn(500);    
        $("#confirmacion").hide();
    }
    
    function Ajax_CargarAccion_Aux_CompletarSolicitud(codigo, estado) {
        var strParametros = "&codigo=" + codigo + "&estado=" + estado + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Afiliador/Completar/AuxVer', 'divVistaMenuPantalla', "divErrorBusqueda", 'SIN_FOCUS', strParametros);
    }

</script>

<div id="divVistaMenuPantalla" align="center">

    <form id="FormularioRegistroLista" method="post">
    
    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('CompletarTitulo') . ' Onboarding' . $nombre_region; ?></div>
            <div class="FormularioSubtituloComentarioNormal ">Con este paso completará el proceso del registro. Así mismo, si lo requiere, establezca si se notificará al solicitante con la plantilla de correo electrónico establecida, y registre un texto personalizado (opcional); el PDF del contrato se generará automáticamente con los datos del registro y elemento digitalizado de la firma.<br /><br />Utilizar función para <span onclick="Ajax_CargarAccion_Aux_CompletarSolicitud(<?php if(isset($arrRespuesta[0]["terceros_id"])){ echo $arrRespuesta[0]["terceros_id"]; } ?>, 2)" style="cursor: pointer; text-decoration: underline;"> cargar manualmente </span> el PDF del contrato. </div>
        
        <div style="clear: both"></div>
        
        <br />

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["terceros_id"])){ echo $arrRespuesta[0]["terceros_id"]; } ?>" />
            
            <input type="hidden" name="tipo_rechazo" value="4" />

            
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    Nombre del Solicitante
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["nombre_persona"]; ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    Correo Electrónico
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["email"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_fecha'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["terceros_fecha"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('tipo_cuenta'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["tipo_cuenta"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('onboarding_numero_cuenta'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["onboarding_numero_cuenta"]; ?>
                </td>

            </tr>
            
            <?php $strClase = "FilaGris"; ?>
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    ¿Notificar al solicitante por Correo Electrónico?
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Puede editar la plantilla de Correo 'Onboarding - Notificar Completado'." data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["rechazado_envia"]; ?>
                </td>
                
            </tr>
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <i class='fa fa-angle-double-right' aria-hidden='true'></i> Texto Personalizado
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="El texto es opcional. El texto personalizado se enviará donde se coloque la variable {onboarding_completar_texto}" data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["rechazado_texto"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <i class='fa fa-angle-double-right' aria-hidden='true'></i> (Opcional) Seleccione los PDF a adjuntar
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Es opcional puede proceder sin adjuntar PDFs. Los documentos listados corresponden a los configurados en el apartado de Tipos de Registro y fueron marcados como 'Se Envía'" data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">
                        
                    <?php

                        if(isset($arrDocs[0]))
                        {
                            $i = 0;
                            $checked = '';

                            foreach ($arrDocs as $key => $value) 
                            {
                                $checked = 'checked="checked"';
                                //$checked = '';
                                
                                echo '<input id="documento' . $i , '" type="checkbox" name="documento_list[]" '. $checked .' value="' . $value["documento_id"] . '">';
                                echo '<label for="documento' . $i , '"><span></span>' . $value["documento_nombre"] . '</label>';
                                echo '<br />';

                                $i++;
                            }
                        }
                        else
                        {
                            echo $this->lang->line('TablaNoRegistrosMinimo');
                        }

                    ?>
                </td>

            </tr>
            
        </table>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Afiliador/Supervisor/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a id="botonGuardado" onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('completar_Pregunta'); ?></div>
        
            <div style="clear: both"></div>
        
            <br />

        <div class="PreguntaConfirmar">
            <?php echo $this->lang->line('PreguntaContinuar'); ?>
        </div>

        <div class="Botones2Opciones">
            <a onclick="OcultarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a id="btnGuardarDatosLista" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>
        
		<br />

        <?php if (isset($respuesta)) { ?>
            <div class="mensajeBD"> 
                <div style="padding: 15px;">
                    <?php echo $respuesta ?>
                </div>
            </div>
        <?php } ?>

        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
    </div>
        
        
    </form>  
</div>