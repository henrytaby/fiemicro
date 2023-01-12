<script type="text/javascript">

    function Ajax_CargarAccion_ConfirmaAprobar(codigo, estado) {
            
        var strParametros = "&codigo_solicitud_conf=" + codigo + "&estado=" + estado + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Afiliador/Aprobar/Guardar', 'divVistaMenuPantalla', "divErrorListaResultado", '', strParametros);
    }
    
</script>

<div id="divVistaMenuPantalla" align="center">
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class='PreguntaTitulo'> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class='PreguntaTexto '>
                
                <table class="tablaresultados Mayuscula" style="width: 95% !important;" border="0">

                    <?php $strClase = "FilaGris"; ?>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('terceros_columna3'); ?>
                        </td>

                        <td style="width: 70%; font-weight: normal;">                
                            <?php echo $arrRespuesta[0]["terceros_nombre"] . ' <i>(' . PREFIJO_TERCEROS . $arrRespuesta[0]["terceros_id"] . ')</i>'; ?>
                        </td>

                    </tr>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('tipo_cuenta'); ?>
                        </td>

                        <td style="width: 70%; font-weight: normal;">                
                            <?php echo $arrRespuesta[0]["tipo_cuenta"] . ' <i>(' . $this->mfunciones_generales->GetValorCatalogo($value["tercero_asistencia"], 'tercero_asistencia') . ')</i>'; ?>
                        </td>

                    </tr>
                    
                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('f_cobis_actual_intento'); ?>
                        </td>

                        <td style="width: 70%; font-weight: normal;">
                            <?php echo $arrRespuesta[0]["f_cobis_actual_intento"] . ' de ' . $conf_intentos; ?>
                        </td>

                    </tr>

                </table>
                
            </div>
        
            <?php echo $this->mfunciones_generales->getTextConfirm(); ?>
            
            
            
            <div style="clear: both"></div>
        
            <br />

        <div class="PreguntaConfirmar">
            <?php echo $this->lang->line('PreguntaContinuar'); ?>
        </div>

        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarAccion_BandejaSolicitud(<?php echo $estado; ?>);" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarAccion_ConfirmaAprobar('<?php echo $codigo_solicitud; ?>', <?php echo $estado; ?>)" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>

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