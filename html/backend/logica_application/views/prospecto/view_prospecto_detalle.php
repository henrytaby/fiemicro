    
<script type="text/javascript">

    function VerTablaResumen(id) {

        $("#"+id).slideToggle();
    }

    function Ajax_CargarAccion_DetalleSolCred(codigo, vista) {
        var strParametros = "&codigo=" + codigo + "&vista=" + vista;
        Ajax_CargadoGeneralPagina('SolWeb/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }

</script>
    

    <div style="overflow-y: auto; height: 400px;">
        
        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('DetalleProspectoTitulo'); ?></div>

        <div style="clear: both"></div>

        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php

            if(count($arrRespuesta[0]) > 0)
            {

            ?>
            
                <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaResumen('1');">
                    <i class='fa fa-eye' aria-hidden='true'></i> Información del Cliente
                </div>
            
                <div id="1">
                
                    <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                        <?php $strClase = "FilaGris"; ?>

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('sol_estudio'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php
                                    
                                    // Check si el prospecto fue creado a través de una solicitud de crédito
                                    $sol_cred = $this->mfunciones_microcreditos->ObtenerSol_from_Pros($arrRespuesta[0]["prospecto_id"]);
                                    
                                    if((int)$sol_cred->sol_id > 0)
                                    {
                                        $aux_separador = '<br /> <i class="fa fa-angle-double-right" aria-hidden="true"></i> ';
                                        
                                        echo '<span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleSolCred(' . (int)$sol_cred->sol_id . ', 0)"> <b> Ver Detalle <i class="fa fa-external-link" aria-hidden="true"></i> </b></span>';
                                        
                                        echo '<br />';
                                        
                                        echo $aux_separador . ' Código Solicitud de Crédito: SOL_' . $sol_cred->sol_id;
                                        echo $aux_separador . ' Monto Solicitado: ' . $this->mfunciones_microcreditos->GetValorCatalogo($sol_cred->sol_moneda, 'sol_moneda') . ' ' . number_format($sol_cred->sol_monto, 2, '.', ',');
                                    }
                                    else
                                    {
                                        echo "No";
                                    }
                                
                                ?>
                            </td>
                        </tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('general_categoria'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["general_depende_nombre"]; ?>
                            </td>
                        </tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('prospecto_id'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo PREFIJO_PROSPECTO . $arrRespuesta[0]["prospecto_id"]; ?>
                            </td>
                        </tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('import_campana'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["camp_nombre"]; ?>
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
                                <?php echo $this->lang->line('regionaliza_nombre'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetProspectoRegion($arrRespuesta[0]["prospecto_id"]); ?>
                            </td>
                        </tr>

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('prospecto_evaluacion'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]['prospecto_evaluacion'], 'prospecto_evaluacion'); ?>
                            </td>
                        </tr>
                        
                    </table>
                    
                    <br />
                    
                    <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('general_solicitante'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["general_solicitante"]; ?>
                            </td>
                        </tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('general_ci'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo "C.I. " . $arrRespuesta[0]['general_ci'] . " " . $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]['general_ci_extension'], 'extension_ci'); ?>
                            </td>
                        </tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('general_telefono'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["general_telefono"]; ?>
                            </td>
                        </tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('general_email'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["general_email"]; ?>
                            </td>
                        </tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('general_direccion'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["general_direccion"]; ?>
                            </td>
                        </tr>
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('general_actividad'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["general_actividad"]; ?>
                            </td>
                        </tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('general_destino'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["general_destino"]; ?>
                            </td>
                        </tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('general_interes'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["general_interes"]; ?>
                            </td>
                        </tr>
                        
                    </table>
                    
                    <br />
                    
                    <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('prospecto_actividades'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["prospecto_id"], 'lead_actividades'); ?>
                            </td>

                        </tr>
                            
                    </table>

                </div>
                
                <br />

                <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaResumen('2');">
                    <i class='fa fa-eye' aria-hidden='true'></i> Unidades Familiares
                </div>
            
                <div style="display: none;" id="2">

                    <?php
                    
                    // Listado de información del Prospecto y sus dependencias
                    $arrFamiliares = $this->mfunciones_logica->select_info_dependencia($arrRespuesta[0]["prospecto_id"]);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrFamiliares);

                    if (isset($arrFamiliares[0])) 
                    {
                        $i = 1;
                        
                        foreach ($arrFamiliares as $key => $value) 
                        {
                            $calculo_lead = $this->mfunciones_generales->CalculoLead($value["prospecto_id"], 'ingreso_ponderado');

                        ?>
                    
                            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                                <tr class="FilaBlanca">

                                    <td style="width: 30%; font-weight: bold;">
                                        <?php 
                                        
                                        echo $this->lang->line('general_unidad_familiar') . ' ' . $i++ . '<br />'; 
                                        
                                        echo "<div style='text-align: right;'>";
                                        
                                            // Icono de Tipo de Registro
                                            if($value['general_categoria'] == 1) { echo ' <i class="fa fa-user-circle-o" aria-hidden="true"></i> '; }
                                            if($value['general_categoria'] == 2) { echo ' <i class="fa fa-users" aria-hidden="true"></i> '; }

                                            // Icono del Rubro

                                            echo $this->mfunciones_generales->GetValorCatalogo($value['camp_id'], 'icono_rubro');

                                            if($value['prospecto_principal'] == 1) { echo ' <i class="fa fa-star" aria-hidden="true"></i> '; }
                                        
                                        echo "</div>";
                                            
                                        ?>
                                    </td>
                                    
                                    <td style="width: 70%;">
                                        <?php
                                        
                                        echo $value["general_solicitante"];
                                        
                                        echo "<br />C.I. " . $value['general_ci'] . " " . $this->mfunciones_generales->GetValorCatalogo($value['general_ci_extension'], 'extension_ci') . " | Teléfono: " . $value['general_telefono'];
                                        
                                        ?>
                                    </td>
                                </tr>
                                
                                <tr class="FilaGris">
                                    
                                    <td style="width: 30%; font-weight: bold;">
                                        <?php echo $this->lang->line('import_campana'); ?>
                                    </td>
                                    
                                    <td style="width: 70%;">
                                        <?php echo $this->mfunciones_generales->GetValorCatalogo($value['camp_id'], 'nombre_rubro'); ?>
                                    </td>
                                    
                                </tr>
                                
                                <tr class="FilaBlanca">
                                    
                                    <td style="width: 30%; font-weight: bold;">
                                        <?php echo $this->lang->line('ingreso_mensual_promedio'); ?>
                                    </td>
                                    
                                    <td style="width: 70%;">
                                        Bs. <?php echo number_format($calculo_lead->ingreso_mensual_promedio, 2, ',', '.'); ?>
                                    </td>
                                    
                                </tr>

                            </table>
                    
                            <br />

                    <?php
                    
                        }
                    }
                    
                    ?>
                    
                </div>
            
                <br />

                <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaResumen('3');">
                    <i class='fa fa-eye' aria-hidden='true'></i> Etapa Actual del Cliente
                </div>
            
                <div style="display: none;" id="3">

                    <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('prospecto_etapa_nombre'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["etapa_nombre"]; ?>
                            </td>
                        </tr>

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('prospecto_etapa_fecha'); ?> 
                            </td>

                            <td style="width: 70%;">
                                <?php 
                                
                                    echo $arrRespuesta[0]["fecha_derivada_etapa"];
                                    
                                    echo "&nbsp;";
                                    if($arrRespuesta[0]["prospecto_etapa"] != 6 && $arrRespuesta[0]["prospecto_etapa"] != 23 && $arrRespuesta[0]["prospecto_etapa"] != 24)
                                    {
                                        echo $this->mfunciones_generales->TiempoEtapaColor($arrRespuesta[0]["tiempo_etapa"]);
                                    }
                                
                                ?>
                            </td>
                        </tr>

                    </table>

                </div>
                
                <?php
                
                if($arrRespuesta[0]["general_categoria"] == 1)
                {
                
                ?>
                
                    <br />

                    <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaResumen('4');">
                        <i class='fa fa-eye' aria-hidden='true'></i> Información de Registro del Cliente
                    </div>

                    <div style="display: none;" id="4">

                        <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('prospecto_fecha_asignacion'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["prospecto_fecha_asignacion"]; ?>
                                </td>
                            </tr>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('prospecto_checkin'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["prospecto_checkin"], 'si_no'); ?>
                                </td>
                            </tr>

                            <?php
                            if($arrRespuesta[0]["prospecto_checkin"] > 0)
                            {
                            ?>

                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <?php echo $this->lang->line('prospecto_checkin_fecha'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["prospecto_checkin_fecha"]; ?>
                                    </td>
                                </tr>
                                
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <?php echo $this->lang->line('prospecto_checkin_geo'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <a href="https://maps.google.com/?q=<?php echo $arrRespuesta[0]["prospecto_checkin_geo"]; ?>" target="_blank"> <i class="fa fa-map-marker" aria-hidden="true"></i> Ver Mapa </a>
                                    </td>
                                </tr>

                            <?php
                            }
                            ?>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('prospecto_consolidado'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["prospecto_consolidado"], 'si_no'); ?>
                                </td>
                            </tr>

                            <?php
                            if($arrRespuesta[0]["prospecto_consolidado"] > 0)
                            {
                            ?>

                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <?php echo $this->lang->line('prospecto_consolidar_fecha'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["prospecto_consolidar_fecha"]; ?>
                                    </td>
                                </tr>
                                
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <?php echo $this->lang->line('prospecto_consolidado_geo'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <a href="https://maps.google.com/?q=<?php echo $arrRespuesta[0]["prospecto_consolidar_geo"]; ?>" target="_blank"> <i class="fa fa-map-marker" aria-hidden="true"></i> Ver Mapa </a>
                                    </td>
                                </tr>

                            <?php
                            }
                            ?>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('prospecto_estado_actual'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["prospecto_estado_actual"], 'estado_actual'); ?>
                                </td>
                            </tr>

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
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('prospecto_jda_eval'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["prospecto_jda_eval"], 'prospecto_evaluacion'); ?>
                                </td>
                            </tr>
                            
                            <?php
                            if((int)$arrRespuesta[0]["prospecto_jda_eval"] == 1 || (int)$arrRespuesta[0]["prospecto_jda_eval"] == 2 || (int)$arrRespuesta[0]["prospecto_jda_eval"] == 99)
                            {
                            ?>
                            
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <?php echo $this->lang->line('prospecto_jda_eval_usuario'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["prospecto_jda_eval_usuario"]; ?>
                                    </td>
                                </tr>
                                
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <?php echo $this->lang->line('prospecto_jda_eval_fecha'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["prospecto_jda_eval_fecha"]; ?>
                                    </td>
                                </tr>
                                
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <?php echo $this->lang->line('prospecto_jda_eval') . ' - ' . $this->lang->line('prospecto_jda_eval_texto'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["prospecto_jda_eval_texto"]; ?>
                                    </td>
                                </tr>
                            
                            <?php
                            }
                            ?>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('registro_num_proceso'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo ((int)$arrRespuesta[0]["registro_num_proceso"] <=0 ? $this->lang->line('prospecto_no_evaluacion') : $arrRespuesta[0]["registro_num_proceso"]); ?>
                                </td>
                            </tr>
                                
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('prospecto_desembolso'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["prospecto_desembolso"], 'si_no'); ?>
                                </td>
                            </tr>
                            
                            <?php
                            if((int)$arrRespuesta[0]["prospecto_desembolso"] == 1)
                            {
                            ?>
                            
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <?php echo $this->lang->line('prospecto_desembolso_monto'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["prospecto_desembolso_monto"]; ?>
                                    </td>
                                </tr>
                            
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <?php echo $this->lang->line('prospecto_desembolso_usuario'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["prospecto_desembolso_usuario"]; ?>
                                    </td>
                                </tr>
                                
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <?php echo $this->lang->line('prospecto_desembolso_fecha'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["prospecto_desembolso_fecha"]; ?>
                                    </td>
                                </tr>
                            
                            <?php
                            }
                            ?>

                        </table>

                    </div>

                <?php
                
                }
                
                ?>
                    
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