<script type="text/javascript">

    function Visor_DocumentoTerceros(documento_base64, documento, tipo_aux) {
		
        var r = Math.random().toString(36).substr(2,16);
        var type = 'ter';
        window.open('Documento/Visualizar?' + r + '&type=' + type + '&documento_base64=' + documento_base64 + '&documento=' + documento + '&tipo_aux=' + tipo_aux, '_blank');
		
    }
    
    function Visor_ContratoPDFTer(documento_base64, documento, tipo_aux) {
		
        var r = Math.random().toString(36).substr(2,16);
        var type = 'ter_contrato';
        window.open('Documento/Visualizar?' + r + '&type=' + type + '&documento_base64=' + documento_base64 + '&documento=' + documento + '&tipo_aux=' + tipo_aux, '_blank');
		
    }
    
    function Ajax_CargarAccion_HistorialDoc(codigo_documento, codigo_prospecto) {
        var strParametros = '&codigo_documento=' + codigo_documento + '&codigo_prospecto=' + codigo_prospecto;
        Ajax_CargadoGeneralPagina('Afiliador/Documento/Historico', 'divElementoFlotante', 'divErrorBusqueda', '', strParametros);
    }

</script>
    
    <div id="FormularioVentanaAuxiliar" style="overflow-y: auto;">

        <div class="FormularioSubtitulo"> <?php echo "<i class='fa fa-camera' aria-hidden='true'></i> Elementos Digitalizados <br />" . $arrTerceros[0]['terceros_nombre'] . '<br /><span style="font-size: 0.6em; font-style: italic; font-weight: normal;"> <i class="fa fa-cogs" aria-hidden="true"></i> Proceso ' . $this->mfunciones_generales->GetValorCatalogo($arrTerceros[0]["tercero_asistencia"], 'tercero_asistencia') . '</span>'; ?></div>
        
        <div style="clear: both"></div>

        <br />

        <form id="FormularioRegistroLista" method="post">

            <input type="hidden" name="codigo_prospecto" id="codigo_prospecto" value="<?php echo $estructura_id; ?>" />
            
            <?php

            if(count($arrRespuesta[0]) > 0)
            {

            ?>

                <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                    <?php $strClase = "FilaGris"; ?>

                    <?php

                    foreach ($arrRespuesta as $key => $value) 
                    {
                    
                    ?>
                    
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 50%;">
                                <strong> <i class="fa fa-clone" aria-hidden="true"></i> <?php echo $value["documento_detalle"]; ?></strong>
                            </td>
                            
                            <td style="width: 35%;">
                                <?php 

                                if(!$value["documento_digitalizado"])
                                {
                                    echo $this->lang->line('documento_no_digitalizado');
                                }
                                else
                                {
                                ?>                                
                                    <span style="float: left !important; margin-right: 10px;" class="EnlaceSimple" onclick="Visor_DocumentoTerceros('<?php echo $estructura_id; ?>', '<?php echo $value["documento_id"]; ?>', '<?php echo ($value["documento_id"]==20 ? 1 : $arrTerceros[0]['tercero_asistencia']); ?>')">
                                        <strong><i class="fa fa-eye" aria-hidden="true"></i> Ver Digitalizado</strong>
                                    </span>
                                
                                    <?php 

                                    if($arrTerceros[0]['tercero_asistencia'] == 1)
                                    {
                                    ?>
                                        <span style="float: left !important;" class="EnlaceSimple" onclick="Ajax_CargarAccion_HistorialDoc('<?php echo $value["documento_id"]; ?>', '<?php echo $estructura_id; ?>')">
                                            <strong><i class="fa fa-history" aria-hidden="true"></i> <?php echo $this->lang->line('documento_si_digitalizado_historial'); ?></strong>
                                        </span>
                                
                                    <?php
                                    }
                                    ?>
                                
                                <?php
                                }
                                ?>
                                
                            </td>
                            
                        </tr>
                        
                    <?php
                    }
                    
                    if($arrTerceros[0]['tercero_asistencia'] == 0)
                    {
                    ?>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 50%;">
                                    <strong> <i class="fa fa-clone" aria-hidden="true"></i> <?php echo $this->mfunciones_generales->GetDocumentoNombre(20); ?></strong>
                                </td>

                                <td style="width: 35%;">
                                    <?php 

                                    if(!$this->mfunciones_generales->GetInfoTercerosDigitalizado($estructura_id, 20, 'existe'))
                                    {
                                        echo $this->lang->line('documento_no_digitalizado');
                                    }
                                    else
                                    {
                                    ?>                                
                                        <span style="float: left !important; margin-right: 10px;" class="EnlaceSimple" onclick="Visor_DocumentoTerceros('<?php echo $estructura_id; ?>', '20', '1')">
                                            <strong><i class="fa fa-eye" aria-hidden="true"></i> Ver Digitalizado</strong>
                                        </span>

                                    <?php
                                    }
                                    ?>

                                </td>

                            </tr>
                        
                    <?php
                    }
                    
                    if($this->mfunciones_generales->GetInfoTercerosDigitalizadoPDFaux($estructura_id, 13, 'existe'))
                    {
                    ?>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 50%;">
                                <strong> <i class="fa fa-clone" aria-hidden="true"></i> PDF del Contrato </strong>
                            </td>
                            <td style="width: 35%;">
                                <span style="float: left !important; margin-right: 10px;" class="EnlaceSimple" onclick="Visor_ContratoPDFTer('<?php echo $estructura_id; ?>', '13', '1')">
                                    <strong><i class="fa fa-eye" aria-hidden="true"></i> Ver Digitalizado</strong>
                                </span>
                            </td>
                            
                        </tr>
                        
                    <?php
                    }
                    ?>

                </table>

            <?php

            }

            else
            {
                echo $this->lang->line('TablaNoResultados');
            }

            ?>
            
        </form>

        <br /><br />
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
    </div>