<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Afiliador/Rechazar/Guardar',
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

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('RechazarTitulo') . ' Onboarding' . ($arrRespuesta[0]["ws_segip_flag_verificacion"]==1 && $arrRespuesta[0]["segip_operador_resultado"]!=2 ? ' - (' . $this->lang->line('ValOperOpcion') . ')' : ''); ?> </div>
            <div class="FormularioSubtituloComentarioNormal ">El rechazo debe ser justificado. Indique la razón por la que se rechaza la Solicitud. Así mismo establezca si se notificará al solicitante con la plantilla de correo electrónico establecido, y registre un texto personalizado (opcional)</div>
        
        <div style="clear: both"></div>
        
        <br />

        <form id="FormularioRegistroLista" method="post">

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
                    Justificación
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["solicitud_observacion"]; ?>
                </td>

            </tr>
            <?php $strClase = "FilaGris"; ?>
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    ¿Notificar al solicitante por Correo Electrónico?
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Puede editar la plantilla de Correo 'Onboarding - Notificar Rechazo'." data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["rechazado_envia"]; ?>
                </td>
            </tr>
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <i class='fa fa-angle-double-right' aria-hidden='true'></i> Texto Personalizado
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="El texto es opcional. El texto personalizado se enviará donde se coloque la variable {onboarding_rechazo_texto}" data-balloon-pos="right"> </span>
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
                                //$checked = 'checked="checked"';
                                $checked = '';
                                
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

        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            
            <?php
            
                $direccion_bandeja = 'Menu/Principal';
            
                if(isset($_SESSION['direccion_bandeja_actual']))
                {
                    $direccion_bandeja = "Ajax_CargarOpcionMenu('" . $_SESSION['direccion_bandeja_actual'] . "');";
                }
            
                if($arrRespuesta[0]["ws_segip_flag_verificacion"] == 1 && $arrRespuesta[0]["segip_operador_resultado"] != 2 && $arrRespuesta[0]["terceros_estado"] == 16)
                {
                    $direccion_bandeja = "Ajax_CargarAccion_ValOper(" . $arrRespuesta[0]["terceros_id"] . ", 16);";
                }
                
            ?>
            
            <a onclick="<?php echo $direccion_bandeja; ?>" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('rechazar_Pregunta'); ?></div>
        
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
</div>