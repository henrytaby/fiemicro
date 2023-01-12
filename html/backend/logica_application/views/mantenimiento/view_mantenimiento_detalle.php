    
<script type="text/javascript">

    function VerTablaResumenM(id) {

        $("#"+id).slideToggle();
    }
    
    function Visor_DocumentoMantenimiento(documento_base64, documento) {
		
        var r = Math.random().toString(36).substr(2,16);
        var type = 'mto';
        window.open('Documento/Visualizar?' + r + '&type=' + type + '&documento_base64=' + documento_base64 + '&documento=' + documento, '_blank');
		
    }

</script>
    

    <div style="overflow-y: auto; height: 400px;">
        
        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('DetalleMantenimientoTitulo'); ?></div>

        <div style="clear: both"></div>

        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php

            if(count($arrRespuesta[0]) > 0)
            {

            ?>
            
                <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaResumenM('1');">
                    <i class='fa fa-eye' aria-hidden='true'></i> Información General
                </div>
            
                <div id="1">
                
                    <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                        <?php $strClase = "FilaGris"; ?>

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('mant_id'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo PREFIJO_MANTENIMIENTO . $arrRespuesta[0]["mant_id"]; ?>
                            </td>
                        </tr>

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('ejecutivo_nombre'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["ejecutivo_nombre"]; ?>
                            </td>
                        </tr>

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('mant_estado'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["mant_estado"], 'estado_mantenimiento'); ?>
                            </td>
                        </tr>
                        
                        <?php
                        if($arrRespuesta[0]["mant_estado"] > 0)
                        {
                        ?>
                        
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('mant_tareas_realizadas'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $listadoTareas; ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('mant_completado_fecha'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["mant_completado_fecha"]; ?>
                                </td>
                            </tr>

                        <?php
                        }
                        ?>
                            
                        <?php
                        if($arrRespuesta[0]["mant_documento_adjunto"] != '')
                        {
                        ?>
                        
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('mant_documento_adjunto'); ?>
                                </td>

                                <td style="width: 70%; font-weight: bold;">
                                    <span class="EnlaceSimple" onclick="Visor_DocumentoMantenimiento('<?php echo $arrRespuesta[0]["mant_documento_adjunto"]; ?>', '<?php echo $arrRespuesta[0]["mant_id"]; ?>')">
                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i> <?php echo $this->lang->line('mant_documento_adjunto_detalle'); ?>
                                    </span>
                                </td>
                            </tr>

                        <?php
                        }
                        ?>
                        
                    </table>

                </div>
                
                    <br />

                <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaResumenM('2');">
                    <i class='fa fa-eye' aria-hidden='true'></i> Información Empresa
                </div>
            
                <div style="display: none;" id="2">
                    
                    <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('empresa_nombre'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["empresa_nombre"]; ?>
                            </td>
                        </tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('empresa_categoria'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["empresa_categoria"]; ?>
                            </td>
                        </tr>
                            
                    </table>

                </div>
                
                <br />

                <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaResumenM('4');">
                    <i class='fa fa-eye' aria-hidden='true'></i> Información de Registro del Mantenimiento
                </div>
            
                <div style="display: none;" id="4">

                    <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('mant_fecha_asignacion'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["mant_fecha_asignacion"]; ?>
                            </td>
                        </tr>

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('mant_checkin'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["mant_checkin"], 'si_no'); ?>
                            </td>
                        </tr>

                        <?php
                        if($arrRespuesta[0]["mant_checkin"] > 0)
                        {
                        ?>
                        
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('mant_checkin_fecha'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["mant_checkin_fecha"]; ?>
                                </td>
                            </tr>

                        <?php
                        }
                        ?>

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('cal_visita_ini'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["cal_visita_ini"]; ?>
                            </td>
                        </tr>

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('cal_visita_fin'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["cal_visita_fin"]; ?>
                            </td>
                        </tr>

                    </table>

                </div>

            <?php

            }

            else
            {            
                echo $this->lang->line('TablaNoResultados');
            }

            ?>

        </form>

        <br /><br />
    </div>