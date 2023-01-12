<script type="text/javascript">

    function Visor_DocumentoProspectoHis(documento_base64, documento, type) {
		
        var r = Math.random().toString(36).substr(2,16);
        window.open('Documento/Visualizar?' + r + '&type=' + type + '&documento_base64=' + documento_base64 + '&documento=' + documento, '_blank');

    }

</script>
    
    <div id="FormularioVentanaAuxiliar" style="overflow-y: auto;">

        <div class="FormularioSubtitulo" style="width: 100% !important;"> <?php echo $this->lang->line('DocumentoProspectoTituloHistorial') . $documento_nombre . '<br />' . $general_solicitante; ?></div>
        
        <div style="clear: both"></div>

        <br />

        <div class="Botones2Opciones" style="text-align: left !important;">
            
            <?php
            
                $direccion_documento = 'Ajax_CargarAccion_DocumentoProspecto';
            
                if(isset($_SESSION['funcion_ver_documento']))
                {
                    $direccion_documento = $_SESSION['funcion_ver_documento'];
                }
            
                $accion_volver = $direccion_documento . "('" . $prospecto_codigo . "', '" . $codigo_tipo_persona . "');";
                
                if(isset($_SESSION['aux_flag_reporte_return']))
                {
                    if($_SESSION['aux_flag_reporte_return'] == 1)
                    {
                        $accion_volver = 'Ajax_CargarAccion_RegistroReturn(' . $prospecto_codigo . ', ' . $codigo_tipo_persona . ');';
                    }
                }
            ?>
            
            <a onclick="<?php echo $accion_volver; ?>" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
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
                                <strong> N° </strong>
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
                                    
                                    <?php
                                    
                                    switch ((int)$codigo_tipo_persona) {
                                        case 6:
                                            // Solicitud de Crédito
                                            $tipo_doc = 'sol_h';

                                            break;

                                        case 13:
                                            // Normalizador/Cobrador
                                            $tipo_doc = 'norm_h';

                                            break;
                                        
                                        default:
                                            
                                            $tipo_doc = 'pto_h';
                                            
                                            break;
                                    }
                                    
                                    ?>
                                    
                                    <span class="EnlaceSimple" onclick="Visor_DocumentoProspectoHis('<?php echo $prospecto_codigo; ?>', '<?php echo $value["prospecto_documento_id"]; ?>', '<?php echo $tipo_doc; ?>')">
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