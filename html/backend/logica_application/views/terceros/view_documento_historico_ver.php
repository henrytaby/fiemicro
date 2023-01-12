<script type="text/javascript">

    function Visor_DocumentoProspectoHisTer(documento_base64, documento, tipo_aux) {
		
        var r = Math.random().toString(36).substr(2,16);
        var type = 'ter_h';
        window.open('Documento/Visualizar?' + r + '&type=' + type + '&documento_base64=' + documento_base64 + '&documento=' + documento + '&tipo_aux=' + tipo_aux, '_blank');

    }
    
    <?php
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_HISTORIAL))
    {        
        echo '
                function Ajax_CargarAccion_Historial(codigo) {
                    var strParametros = "&codigo=" + codigo;
                    Ajax_CargadoGeneralPagina("Prospecto/Historial", "divElementoFlotante", "divErrorBusqueda", "", strParametros);
                }
            ';
    }
    ?>

</script>
    
    <div id="FormularioVentanaAuxiliar" style="overflow-y: auto;">

        <div class="FormularioSubtitulo" style="width: 100% !important;"> <?php echo $this->lang->line('DocumentoProspectoTituloHistorial') . $documento_nombre . '<br />' . $arrTerceros[0]['terceros_nombre'] . '<br /><span style="font-size: 0.6em; font-style: italic; font-weight: normal;"> <i class="fa fa-cogs" aria-hidden="true"></i> Proceso ' . $this->mfunciones_generales->GetValorCatalogo($arrTerceros[0]["tercero_asistencia"], 'tercero_asistencia') . '</span>'; ?></div>
        
        <div style="clear: both"></div>

        <br />

        <div class="Botones2Opciones" style="text-align: left !important;">
            
            <?php
                    $direccion_documento = 'Ajax_CargarAccion_DocumentoTerceros';
            ?>
            
            <a onclick="<?php echo $direccion_documento . "('" . $prospecto_codigo . "')"; ?>" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>
        
        <form id="FormularioRegistroLista" method="post">

            <input type="hidden" name="codigo_prospecto" id="codigo_prospecto" value="<?php echo $prospecto_codigo; ?>" />
            
            <?php

            // Flag utilizado para saber si existe alguna observación, caso contrarío se mantendrá con "0".
            $sw_observacion = 0;
            
            if(count($arrRespuesta[0]) > 0)
            {

            ?>

                <table class="tablaresultados Centrado" cellspacing="0" border="1" style="width: 80% !important;">
                    <thead>

                        <tr class="FilaCabecera">

                            <td style="width:10%; text-align: center;">
                                <strong> N2° </strong>
                             </td>

                            <!-- Similar al EXCEL -->

                            <td style="width:45%; text-align: center;">
                               <strong> <?php echo $this->lang->line('observacion_fecha_digitalizacion'); ?> </strong> </td>
                            <td style="width:45%; text-align: center;">
                               <strong> <?php echo $this->lang->line('TablaOpciones'); ?> </strong> </td>

                            <!-- Similar al EXCEL -->

                        </tr>
                    </thead>
                    <tbody>
                    <?php

                    $mostrar = true;
                    if (count($arrRespuesta[0]) == 0) 
                    {
                        $mostrar = false;
                    }

                    if ($mostrar) 
                    {                
                        $i=0;
                        $strClase = "FilaGris";
                        foreach ($arrRespuesta as $key => $value) 
                        {                    
                            $i++;

                            ?> 
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                            <tr class="<?php echo $strClase; ?>">

                                <td style="text-align: center;">
                                    <?php echo $i; ?>
                                </td>

                                <!-- Similar al EXCEL -->

                                <td style="text-align: center;">
                                    <?php echo $value["documento_fecha"]; ?>
                                </td>

                                <td style="text-align: center;">
                                    <span class="EnlaceSimple" onclick="Visor_DocumentoProspectoHisTer('<?php echo $prospecto_codigo; ?>', '<?php echo $value["prospecto_documento_id"]; ?>', '<?php echo $arrTerceros[0]['tercero_asistencia']; ?>')">
                                        <strong><i class="fa fa-eye" aria-hidden="true"></i> <?php echo $this->lang->line('documento_si_digitalizado'); ?></strong>
                                    </span>
                                </td>

                                <!-- Similar al EXCEL -->

                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                                <?php
                                $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca";
                                //endfor;
                            }?>
                </table>

            <?php

            }

            else
            {
                echo $this->lang->line('TablaNoRegistros');
            }

            ?>
            
            <br />
            
            <?php            
            if($sw_observacion == 1)
            {
            ?>
            
                <div style="text-align: center;">
                    <a onclick="RemitirObsDoc('<?php echo $estructura_id; ?>')" class="BotonMinimalista"> <?php echo $this->lang->line('documento_remitir_observación'); ?> </a>
                </div>
            
            <?php            
            }
            ?>
            
        </form>

        <br /><br />
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
    </div>