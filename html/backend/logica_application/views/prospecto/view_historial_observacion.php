    
<script type="text/javascript">

    function VerTablaResumenObs(id) {

        $("#"+id).slideToggle();
    }
    
    function Ajax_CargarAccion_HistorialDoc(codigo_documento, codigo_prospecto, codigo_tipo_persona) {
        var strParametros = '&codigo_documento=' + codigo_documento + '&codigo_prospecto=' + codigo_prospecto + '&codigo_tipo_persona=' + codigo_tipo_persona;
        Ajax_CargadoGeneralPagina('Prospecto/Documento/Historico', 'divElementoFlotante', 'divErrorBusqueda', '', strParametros);
    }

</script>

    <div style="overflow-y: auto; height: 400px;">
        
        <div class="FormularioSubtitulo"> 
            
            <?php 
            
                switch ((int)$codigo_tipo_persona) {
                    
                    // Solicitud de Crédito
                    case 6:

                        $prefijo = 'SOL_';

                        break;

                    // Normalizador/Cobrador
                    case 13:

                        $prefijo = $this->lang->line('norm_prefijo');

                        break;
                    
                    default:
                        
                        $prefijo = PREFIJO_PROSPECTO;
                        
                        break;
                }
            
                echo $this->lang->line('DetalleHistorialObservacion') . ' ' . $prefijo . $codigo_prospecto . '<br />' . $general_solicitante; 
                
            ?>
        
        </div>

        <div style="clear: both"></div>

        <br />

        <form id="FormularioRegistroLista" method="post">

            <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaResumenObs('1');">
                <i class='fa fa-eye' aria-hidden='true'></i> Observaciones a Documentos
            </div>

            <div id="1">

                <?php

                if(count($arrObsDocumentos[0]) > 0)
                {

                ?>

                    <table class="tblListas Centrado " cellspacing="0" border="1" style="width: 100%;">
                        <thead>

                            <tr class="FilaCabecera">

                                <th style="width:4%;">
                                    N°
                                 </th>
                                
                                <!-- Similar al EXCEL -->

                                <th style="width:10%;">
                                   <strong> <?php echo $this->lang->line('observacion_fecha'); ?> </strong> </th>
                                <th style="width:15%;">
                                   <strong> <?php echo $this->lang->line('observacion_usuario_deriva'); ?> </strong> </th>
                                <th style="width:10%;">
                                   <strong> <?php echo $this->lang->line('observacion_tipo'); ?> </strong> </th>                                
                                <th style="width:15%;">
                                   <strong> <?php echo $this->lang->line('observacion_documento'); ?> </strong> </th>
                                <th style="width:26%;">
                                   <strong> <?php echo $this->lang->line('observacion_detalle'); ?> </strong> </th>
                                <th style="width:10%;">
                                   <strong> <?php echo $this->lang->line('observacion_estado'); ?> </strong> </th>
                                <th style="width:10%;">
                                   <strong> <?php echo $this->lang->line('TablaOpciones'); ?> </strong> </th>

                                <!-- Similar al EXCEL -->

                            </tr>
                        </thead>
                        <tbody>
                        <?php

                        $mostrar = true;
                        if (count($arrObsDocumentos[0]) == 0) 
                        {
                            $mostrar = false;
                        }

                        if ($mostrar) 
                        {                
                            $i=0;
                            $strClase = "FilaBlanca";
                            foreach ($arrObsDocumentos as $key => $value) 
                            {                    
                                $i++;

                                ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="text-align: center;">
                                        <?php echo $i; ?>
                                    </td>
                                    
                                    <!-- Similar al EXCEL -->

                                    <td style="text-align: center;">
                                        <?php echo $value["obs_fecha"]; ?>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <?php echo $value["usuario_nombre"]; ?>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <?php echo $value["obs_tipo_detalle"]; ?>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <?php echo $value["documento_nombre"]; ?>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <?php echo $value["obs_detalle"]; ?>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <?php echo $value["obs_estado_detalle"]; ?>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <?php

                                            if(!$value["documento_digitalizado"])
                                            {
                                                if($value["documento_id"] == 0)
                                                {
                                                    echo "Registro en Formulario(s)";
                                                }
                                                else
                                                {
                                                    echo $this->lang->line('documento_no_digitalizado');
                                                }
                                            }
                                            else
                                            {
                                            ?>
                                                <span style="float: left !important;" class="EnlaceSimple" onclick="Ajax_CargarAccion_HistorialDoc('<?php echo $value["documento_id"]; ?>', '<?php echo $value["prospecto_id"]; ?>', '<?php echo $codigo_tipo_persona; ?>')">
                                                    <strong><i class="fa fa-history" aria-hidden="true"></i> <?php echo $this->lang->line('documento_si_digitalizado_historial'); ?></strong>
                                                </span>
                                            <?php
                                            }
                                        ?>
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
                    echo $this->lang->line('TablaNoObservaciones');
                }

                ?>

            </div>

                <br /><br />

            <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaResumenObs('2');">
                <i class='fa fa-eye' aria-hidden='true'></i> Observaciones al Proceso
            </div>

            <div id="2" style="display: none;">

                <?php

                if(count($arrObsProceso[0]) > 0)
                {

                ?>

                    <table class="tblListas Centrado " cellspacing="0" border="1" style="width: 100%;">
                        <thead>

                            <tr class="FilaCabecera">

                                <th style="width:4%;">
                                    N°
                                 </th>
                                
                                <!-- Similar al EXCEL -->

                                <th style="width:10%;">
                                   <strong> <?php echo $this->lang->line('observacion_fecha'); ?> </strong> </th>
                                <th style="width:15%;">
                                   <strong> <?php echo $this->lang->line('observacion_usuario_deriva'); ?> </strong> </th>
                                <th style="width:10%;">
                                   <strong> <?php echo $this->lang->line('observacion_tipo'); ?> </strong> </th>                                
                                <th style="width:15%;">
                                   <strong> <?php echo $this->lang->line('observacion_etapa'); ?> </strong> </th>
                                <th style="width:36%;">
                                   <strong> <?php echo $this->lang->line('observacion_detalle'); ?> </strong> </th>
                                <th style="width:10%;">
                                   <strong> <?php echo $this->lang->line('observacion_estado'); ?> </strong> </th>

                                <!-- Similar al EXCEL -->

                            </tr>
                        </thead>
                        <tbody>
                        <?php

                        $mostrar = true;
                        if (count($arrObsProceso[0]) == 0) 
                        {
                            $mostrar = false;
                        }

                        if ($mostrar) 
                        {
                            $i=0;
                            $strClase = "FilaBlanca";
                            foreach ($arrObsProceso as $key => $value)
                            {                    
                                $i++;

                                ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="text-align: center;">
                                        <?php echo $i; ?>
                                    </td>
                                    
                                    <!-- Similar al EXCEL -->

                                    <td style="text-align: center;">
                                        <?php echo $value["obs_fecha"]; ?>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <?php echo $value["usuario_nombre"]; ?>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <?php echo $value["obs_tipo_detalle"]; ?>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <?php echo $value["etapa_nombre"]; ?>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <?php echo $value["obs_detalle"]; ?>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <?php echo $value["obs_estado_detalle"]; ?>
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
                    echo $this->lang->line('TablaNoObservaciones');
                }

                ?>

            </div>

        </form>

        <br /><br />
    </div>