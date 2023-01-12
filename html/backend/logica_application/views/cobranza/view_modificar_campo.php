<script type="text/javascript">
    
    function Ajax_CargarAccion_ActualizarCampoNorm() {
        var strParametros = "&codigo=" + <?php echo $codigo; ?> + "&tipo=" + <?php echo $tipo; ?> + "&registro_custom_valor=" + $("#registro_custom_valor").val();
        Ajax_CargadoGeneralPagina('Norm/NormModCampo/Guardar', 'divElementoFlotante', "divErrorNormMod", 'CARGA_SOLO_SCRIPTS', strParametros);
    }
    
    function MostrarAccion()
    {
        $("#divConfirmar").hide();
        $("#divAccion").fadeIn(500);
    }
    
    function OcultarAccion()
    {
        $("#divConfirmar").fadeIn(500);
        $("#divAccion").hide();
    }
    
    <?php
    
    switch ($tipo) {
        // Modificar Número de Operación
        case 1:
            ?>

            $('#registro_custom_valor').on('keyup change', function(){
                check_registro_custom_valor();
            });

            function check_registro_custom_valor()
            {
                var valor = parseInt($("#registro_custom_valor").val() || 0);

                valor = valor.toString();

                if(!( /[^0-9]/.test( valor ) || valor.length != <?php echo (int)$this->lang->line('registro_num_proceso_cantidad'); ?>))
                {
                    $('#registro_custom_valor_label_error').hide();
                    $('#registro_custom_valor_label_ok').show();
                }
                else
                {
                    $('#registro_custom_valor_label_ok').hide();
                    $('#registro_custom_valor_label_error').show();
                }
            }

            <?php
            break;
        
        // Modificar Agencia Asociada
        case 2:
            ?>

            $("#registro_custom_valor").on('change', function(){
                check_registro_custom_valor();
            });

            function check_registro_custom_valor()
            {
                if($("#registro_custom_valor").val() != -1)
                {
                    $('#registro_custom_valor_label_error').hide();
                    $('#registro_custom_valor_label_ok').show();
                }
                else
                {
                    $('#registro_custom_valor_label_ok').hide();
                    $('#registro_custom_valor_label_error').show();
                }
            }

            <?php
            break;

        default:
            break;
    }
    ?>
        
    check_registro_custom_valor();
    
</script>
    
    <div id="FormularioVentanaAuxiliar" style="overflow-y: auto;">

        <div class="FormularioSubtitulo" style="width: 100%;"> <?php echo $titulo_ventana . '<br />' . $general_solicitante; ?></div>
        <div class="FormularioSubtituloComentarioNormal" style="width: 100%;"><?php echo $this->lang->line('campo_NormModCampo_ayuda'); ?></div>
        
        <div style="clear: both"></div>

        <div id="divErrorNormMod" class="mensajeBD"> </div>
        
        <table class="tblListas Centrado" style="width: 80% !important; padding: 0px; background-color: #f8f8f8;" border="0">

            <tr class="FilaGris">
                <th colspan="2" style="text-align: center; font-weight: bold; width: 100%; height: 5px;">
                </th>
            </tr>
            
            <tr class="FilaBlanca">
                <td style="text-align: left; padding-left: 20px; font-weight: bold; width: 35%; height: 25px;">
                    <?php echo $this->lang->line('campo_NormModCampo_actual'); ?>
                </td>
                <td style="text-align: left; padding-left: 20px; font-size: 1.1em; width: 65%;">
                    <?php echo $valor_actual; ?>
                </td>
                
            </tr>
            
            <tr class="FilaBlanca">
                <td style="text-align: left; padding-left: 20px; font-weight: bold; width: 35%;">
                    <?php echo $this->lang->line('campo_NormModCampo_nuevo'); ?>
                    <?php echo str_replace('registro_num_proceso_label', 'registro_custom_valor_label', $this->lang->line('registro_num_proceso_label')); ?>
                </td>
                <td style="text-align: center; font-weight: bold; width: 65%;">
                    
                    <?php
                    
                    switch ($tipo) {
                        // Modificar Número de Operación
                        case 1:

                            echo '<input type="number" autocomplete="off" value="' . $arrRespuesta[0]['registro_num_proceso'] . '" id="registro_custom_valor" name="registro_custom_valor" maxlength="' . (int)$this->lang->line('registro_num_proceso_cantidad') . '" title="" onkeydown="return (event.keyCode != 13);" />';

                            break;
                        
                        // Modificar Agencia Asociada
                        case 2:

                            if(count($arrAgencias[0]) > 0)
                            {
                                echo html_select('registro_custom_valor', $arrAgencias, 'estructura_regional_id', 'estructura_regional_nombre', '', $arrRespuesta[0]['codigo_agencia_fie']);
                            }
                            else
                            {
                                echo $this->lang->line('TablaNoRegistros');
                            }

                            break;

                        default:
                            break;
                    }
                    
                    ?>
                    
                    
                </td>
            </tr>

        </table>
        
        <br /><br />
        
        <div id='divConfirmar' style="text-align: center;">
            <a onclick="MostrarAccion();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div id='divAccion' style="text-align: center; display: none;">
            
            <div class="PreguntaTexto" style="float: none; text-align: center; width: 100%; padding-top: 0px; padding-bottom: 20px;">
                <?php echo $this->lang->line('PreguntaNormModCampoContinuar'); ?>
            </div>
            
            <div class="Botones2Opciones">
                <a onclick="OcultarAccion();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonSalir'); ?> </a>
            </div>

            <div class="Botones2Opciones">
                <a onclick="Ajax_CargarAccion_ActualizarCampoNorm();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
            </div>
        
        </div>
            
        <div style="clear: both"></div>
        
        <br /><br /><br /><br />
        
    </div>