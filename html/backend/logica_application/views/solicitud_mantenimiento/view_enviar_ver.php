<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Solicitud/Enviar/Guardar',
            'FormularioVentanaAuxiliar', 'divErrorListaResultado');
</script>
    
    <div id="FormularioVentanaAuxiliar">

        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('DetalleEnvioTitulo'); ?></div>
        <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('DetalleEnvioSubTitulo'); ?></div>
        
        <div style="clear: both"></div>

        <br />

        <form id="FormularioRegistroLista" method="post">

            <input type="hidden" name="codigo_prospecto" id="codigo_prospecto" value="<?php echo $codigo_prospecto; ?>" />
            
            <?php

            if(count($arrRespuesta[0]) > 0)
            {

            ?>

                <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                    <?php $strClase = "FilaGris"; ?>

                    <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 100%;">
                            <br />
                        
                            <?php

                                if(isset($arrRespuesta[0]))
                                {
                                    $i = 0;
                                    $checked = '';

                                    foreach ($arrRespuesta as $key => $value) 
                                    {
                                        $checked = 'checked="checked"';

                                        echo '<input id="documento' . $i , '" type="checkbox" name="documento_list[]" '. $checked .' value="' . $value["documento_id"] . '">';
                                        echo '<label for="documento' . $i , '"><span></span>' . $value["documento_detalle"] . '</label>';
                                        echo '<br /><br />';

                                        $i++;
                                    }
                                }

                            ?>
                        </td>
                    </tr>

                </table>

            <?php

            }

            else
            {            
                echo $this->lang->line('TablaNoResultados');
            }

            ?>

            <br /><br />
            
            <div class="Botones2Opciones" style="float: right;">
                <a id="btnGuardarDatosLista" class="BotonMinimalista"> <?php echo $this->lang->line('ProspectoEnviarDocumentos'); ?> </a>
            </div>
            
        </form>

        <br /><br />
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
    </div>