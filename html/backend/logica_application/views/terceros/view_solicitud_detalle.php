<script type="text/javascript">

    function VerTablaOnboarding(id) {

        $("#"+id).slideToggle();
    }
    
    <?php
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_USUARIO))
    {        
        echo '
                function Ajax_CargarAccion_DetalleUsuarioDetalle(tipo_codigo, codigo_usuario) {
                    var strParametros = "&tipo_codigo=" + tipo_codigo + "&codigo_usuario=" + codigo_usuario;
                    Ajax_CargadoGeneralPagina("Usuario/Detalle", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
                }
            ';
    }
    ?>
        
    function Visor_ContratoPDF(documento_base64, documento, tipo_aux) {
		
        var r = Math.random().toString(36).substr(2,16);
        var type = 'ter_contrato';
        window.open('Documento/Visualizar?' + r + '&type=' + type + '&documento_base64=' + documento_base64 + '&documento=' + documento + '&tipo_aux=' + tipo_aux, '_blank');
		
    }
    
    function RefreshTrackingFlujoCOBIS(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina("Afiliador/Tracking/FlujoCOBIS", "divTrackingFlow", "divTrackingFlow", "SLASH", strParametros);
    }

</script>

    <div style="overflow-y: auto; height: 400px;">
    
        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('DetalleRegistroTitulo'); ?></div>
        
        <div style="clear: both"></div>

        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php

            if(count($arrRespuesta[0]) > 0)
            {

            ?>

                <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaOnboarding('1');">
                    <i class='fa fa-eye' aria-hidden='true'></i> Información del Registro
                </div>
            
                <div style="display: none;" id="1">
            
                    <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                        <?php $strClase = "FilaGris"; ?>

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('tipo_cuenta'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["tipo_cuenta"]; ?>
                            </td>
                        </tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('tercero_asistencia'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["tercero_asistencia"], 'tercero_asistencia'); ?>
                            </td>
                        </tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                Agencia Asociada
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->ObtenerNombreRegionCodigo($arrRespuesta[0]["codigo_agencia_fie"]); ?>
                            </td>
                        </tr>

                        <?php
                        if($arrRespuesta[0]["tercero_asistencia"] == 1)
                        {
                        ?>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    | <?php echo $this->lang->line('terceros_agente_nombre'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php 
                                    
                                    echo $arrRespuesta[0]["agente_nombre"]; 
                                    
                                    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DETALLE_USUARIO))
                                    {
                                        echo ' <br /><span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleUsuarioDetalle(0, ' . $arrRespuesta[0]["agente_codigo"] . ')"> <i class="fa fa-user-circle-o" aria-hidden="true"></i> Detalle Agente </span>';
                                    }
                                    
                                    ?>
                                    
                                </td>
                            </tr>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    | <?php echo $this->lang->line('terceros_agente_agencia'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["agente_agencia"]; ?>
                                </td>
                            </tr>

                        <?php
                        }
                        ?>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('terceros_fecha'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["terceros_fecha"]; ?>
                            </td>
                        </tr>

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('terceros_estado'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["terceros_estado"], 'terceros_estado'); ?>
                            </td>
                        </tr>

                        <?php
                        if($arrRespuesta[0]["tercero_asistencia"] == 0)
                        {
                        ?>
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('ws_segip_codigo_resultado'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["ws_segip_codigo_resultado"]; ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('ws_cobis_codigo_resultado'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["ws_cobis_codigo_resultado"]; ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('ws_selfie_codigo_resultado'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["ws_selfie_codigo_resultado"]; ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('ws_ocr_codigo_resultado'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["ws_ocr_codigo_resultado"]; ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class='fa fa-angle-double-right' aria-hidden='true'></i> <?php echo $this->lang->line('ws_ocr_name_valor'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo ((int)$arrRespuesta[0]["ws_ocr_name_similar"] == -1 ? 'No Registrado' : $arrRespuesta[0]["ws_ocr_name_valor"]); ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class='fa fa-angle-double-right' aria-hidden='true'></i> <?php echo $this->lang->line('ws_ocr_name_similar'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo ((int)$arrRespuesta[0]["ws_ocr_name_similar"] == -1 ? 'No Registrado' : $arrRespuesta[0]["ws_ocr_name_similar"] . '%'); ?>
                                </td>
                            </tr>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('ws_segip_flag_verificacion'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["ws_segip_flag_verificacion"], 'si_no'); ?>
                                </td>
                            </tr>
                            
                            <?php
                            if($arrRespuesta[0]["ws_segip_flag_verificacion"] == 1)
                            {
                            ?>

                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <i class='fa fa-angle-double-right' aria-hidden='true'></i> <?php echo $this->lang->line('segip_operador_resultado'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["segip_operador_resultado"]; ?>
                                    </td>
                                </tr>
                            
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <i class='fa fa-angle-double-right' aria-hidden='true'></i> <?php echo $this->lang->line('segip_operador_fecha'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["segip_operador_fecha"]; ?>
                                    </td>
                                </tr>

                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <i class='fa fa-angle-double-right' aria-hidden='true'></i> <?php echo $this->lang->line('segip_operador_usuario'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["segip_operador_usuario"]; ?>
                                    </td>
                                </tr>

                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <i class='fa fa-angle-double-right' aria-hidden='true'></i> <?php echo $this->lang->line('segip_operador_texto'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["segip_operador_texto"]; ?>
                                    </td>
                                </tr>

                        <?php
                            }
                        }
                        ?>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('aprobado'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["aprobado"], 'si_no'); ?>
                            </td>
                        </tr>

                        <?php
                        if($arrRespuesta[0]["aprobado"] == 1)
                        {
                        ?>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('aprobado_fecha'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["aprobado_fecha"]; ?>
                                </td>
                            </tr>

                            <?php
                            if($arrRespuesta[0]["tercero_asistencia"] == 1)
                            {
                            ?>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('aprobado_usuario'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->getNombreUsuario($arrRespuesta[0]["aprobado_usuario"]); ?>
                                </td>
                            </tr>

                        <?php
                            }
                        
                        }
                        ?>

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('cobis'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["cobis"], 'si_no'); ?>
                            </td>
                        </tr>
                        
                        <?php
                        if($arrRespuesta[0]["cobis"] == 1)
                        {
                        ?>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('onboarding_numero_cuenta'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["onboarding_numero_cuenta"]; ?>
                                </td>
                            </tr>
                        
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('cobis_fecha'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["cobis_fecha"]; ?>
                                </td>
                            </tr>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('cobis_usuario'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->getNombreUsuario($arrRespuesta[0]["cobis_usuario"]); ?>
                                </td>
                            </tr>

                        <?php
                        }
                        ?>
                            
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('completado'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["completado"], 'si_no'); ?>
                            </td>
                        </tr>
                                
                        <?php
                        if($arrRespuesta[0]["completado"] == 1)
                        {
                        ?>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('completado_fecha'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["completado_fecha"]; ?>
                                </td>
                            </tr>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('completado_usuario'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->getNombreUsuario($arrRespuesta[0]["completado_usuario"]); ?>
                                </td>
                            </tr>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <i class='fa fa-angle-double-right' aria-hidden='true'></i>  PDF del Contrato
                                    </td>

                                    <td style="width: 70%;">
                                        <?php
                                        
                                        if($this->mfunciones_generales->GetInfoTercerosDigitalizadoPDFaux($arrRespuesta[0]['terceros_id'], 13, 'existe'))
                                        {
                                        ?>
                                            <span style="float: left !important; margin-right: 10px;" class="EnlaceSimple" onclick="Visor_ContratoPDF('<?php echo $arrRespuesta[0]['terceros_id']; ?>', '13', '1')">
                                                <strong><i class="fa fa-eye" aria-hidden="true"></i> Ver Documento</strong>
                                            </span>
                                        <?php
                                        }
                                        else
                                        {
                                            echo "No Digitalizado";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('completado_envia'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["completado_envia"], 'si_no'); ?>
                                </td>
                            </tr>
                            
                            <?php
                            if($arrRespuesta[0]["completado_envia"] == 1)
                            {
                            ?>
                            
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <?php echo $this->lang->line('completado_texto'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["completado_texto"]; ?>
                                    </td>
                                </tr>
                                
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        Completado - <?php echo $this->lang->line('adjuntos_enviados'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $this->mfunciones_generales->DocsComa2Lista($arrRespuesta[0]["completado_docs_enviados"]); ?>
                                    </td>
                                </tr>
                            
                        <?php
                        
                            }
                        
                        }
                        ?>
                                
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('notificar_cierre'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["notificar_cierre"], 'si_no'); ?>
                            </td>
                        </tr>
                        
                        <?php
                        if($arrRespuesta[0]["notificar_cierre"] == 1)
                        {
                        ?>
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('notificar_cierre_fecha'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["notificar_cierre_fecha"]; ?>
                                </td>
                            </tr>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('notificar_cierre_usuario'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->getNombreUsuario($arrRespuesta[0]["notificar_cierre_usuario"]); ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('notificar_cierre_causa'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogoDB($arrRespuesta[0]["notificar_cierre_causa"], 'causa_notificar_cierre'); ?>
                                </td>
                            </tr>
							
                        <?php
                        }
                        ?>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('cuenta_cerrada'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["cuenta_cerrada"], 'si_no'); ?>
                            </td>
                        </tr>
                        
                        <?php
                        if($arrRespuesta[0]["cuenta_cerrada"] == 1)
                        {
                        ?>
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('cuenta_cerrada_fecha'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["cuenta_cerrada_fecha"]; ?>
                                </td>
                            </tr>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('cuenta_cerrada_usuario'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->getNombreUsuario($arrRespuesta[0]["cuenta_cerrada_usuario"]); ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('cuenta_cerrada_causa'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogoDB($arrRespuesta[0]["cuenta_cerrada_causa"], 'causa_notificar_cierre'); ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('cuenta_cerrada_envia'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["cuenta_cerrada_envia"], 'si_no'); ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class='fa fa-angle-double-right' aria-hidden='true'></i> <i class="fa fa-clock-o" aria-hidden="true"></i> Flujo Concluido
                                </td>

                                <td style="width: 70%;">
                                    <?php
                                    
                                    $tiempo_total = $this->mfunciones_generales->GetTotalHorasFlujo(5);
                                    
                                    $tiempo = $this->mfunciones_generales->ObtenerHorasLaboral($arrRespuesta[0]["terceros_fecha_plano"], $arrRespuesta[0]["entregado_fecha_plano"], $tiempo_total);
                                    
                                    echo 'Horas laborales: ' . $tiempo->horas_laborales . ' | ' . $tiempo->resultado_texto;
                                    
                                    ?>
                                </td>
                            </tr>
							
                        <?php
                        }
                        ?>
                        
                        
                        
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('entregado'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["entregado"], 'si_no'); ?>
                            </td>
                        </tr>
                                
                        <?php
                        if($arrRespuesta[0]["entregado"] == 1)
                        {
                        ?>
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('entregado_fecha'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["entregado_fecha"]; ?>
                                </td>
                            </tr>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('entregado_usuario'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->getNombreUsuario($arrRespuesta[0]["entregado_usuario"]); ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('entregado_texto'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["entregado_texto"]; ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class="fa fa-clock-o" aria-hidden="true"></i> Flujo Concluido
                                </td>

                                <td style="width: 70%;">
                                    <?php
                                    
                                    $tiempo_total = $this->mfunciones_generales->GetTotalHorasFlujo(5);
                                    
                                    $tiempo = $this->mfunciones_generales->ObtenerHorasLaboral($arrRespuesta[0]["terceros_fecha_plano"], $arrRespuesta[0]["entregado_fecha_plano"], $tiempo_total);
                                    
                                    echo 'Horas laborales: ' . $tiempo->horas_laborales . ' | ' . $tiempo->resultado_texto;
                                    
                                    ?>
                                </td>
                            </tr>
							
                        <?php
                        }
                        ?>
                                
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('rechazado'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["rechazado"], 'si_no'); ?>
                            </td>
                        </tr>

                        <?php
                        if($arrRespuesta[0]["rechazado"] == 1)
                        {
                        ?>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('rechazado_fecha'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["rechazado_fecha"]; ?>
                                </td>
                            </tr>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('rechazado_usuario'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->getNombreUsuario($arrRespuesta[0]["rechazado_usuario"]); ?>
                                </td>
                            </tr>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('terceros_observacion'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo nl2br($arrRespuesta[0]["terceros_observacion"]); ?>
                                </td>
                            </tr>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('rechazado_envia'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["rechazado_envia"], 'si_no'); ?>
                                </td>
                            </tr>
                            
                            <?php
                            if($arrRespuesta[0]["rechazado_envia"] == 1)
                            {
                            ?>
                            
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <?php echo $this->lang->line('rechazado_texto'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["rechazado_texto"]; ?>
                                    </td>
                                </tr>
                                
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <i class='fa fa-angle-double-right' aria-hidden='true'></i> <i class='fa fa-angle-double-right' aria-hidden='true'></i> Rechazo - <?php echo $this->lang->line('adjuntos_enviados'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $this->mfunciones_generales->DocsComa2Lista($arrRespuesta[0]["rechazado_docs_enviados"]); ?>
                                    </td>
                                </tr>
                            
                        <?php
                        
                            }
                        
                        }
                        ?>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('f_cobis_actual_etapa'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php 
                                    echo $arrRespuesta[0]["f_cobis_actual_etapa_detalle"];
                                    
                                    if((int)$arrRespuesta[0]["f_cobis_actual_etapa"] > 0 && (int)$arrRespuesta[0]["f_cobis_actual_etapa"] < 99 && (int)$arrRespuesta[0]["completado"] == 1)
                                    {
                                        echo " <b><i>(Completado Operativamente)</i></b>";
                                    }
                                ?>
                            </td>
                        </tr>
                                
                        <?php
                        if($arrRespuesta[0]["f_cobis_actual_etapa"] > 0)
                        {
                        ?>  
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class='fa fa-angle-double-right' aria-hidden='true'></i><i class='fa fa-angle-double-right' aria-hidden='true'></i> <?php echo $this->lang->line('f_cobis_actual_usuario'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["f_cobis_actual_usuario"]; ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class='fa fa-angle-double-right' aria-hidden='true'></i><i class='fa fa-angle-double-right' aria-hidden='true'></i> <?php echo $this->lang->line('f_cobis_actual_fecha'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["f_cobis_actual_fecha"]; ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class='fa fa-angle-double-right' aria-hidden='true'></i> <?php echo $this->lang->line('f_cobis_actual_intento'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["f_cobis_actual_intento"]; ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class='fa fa-angle-double-right' aria-hidden='true'></i> <?php echo $this->lang->line('f_cobis_codigo'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo ($arrRespuesta[0]["f_cobis_codigo"]=='' ? '<i>No registrado</i>' : $arrRespuesta[0]["f_cobis_codigo"]); ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class='fa fa-angle-double-right' aria-hidden='true'></i> <?php echo $this->lang->line('f_cobis_excepcion'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["f_cobis_excepcion_detalle"]; ?>
                                </td>
                            </tr>
                            
                            <?php
                            if((int)$arrRespuesta[0]["f_cobis_excepcion"] == 1)
                            {
                            ?>
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <i class='fa fa-angle-double-right' aria-hidden='true'></i><i class='fa fa-angle-double-right' aria-hidden='true'></i> <?php echo $this->lang->line('f_cobis_excepcion_motivo'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["f_cobis_excepcion_motivo"] . ((string)$arrRespuesta[0]["f_cobis_excepcion_motivo_texto"]=='' ? '' : '<br /><i>' . $arrRespuesta[0]["f_cobis_excepcion_motivo_texto"] . '</i>'); ?>
                                    </td>
                                </tr>
                                
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <i class='fa fa-angle-double-right' aria-hidden='true'></i><i class='fa fa-angle-double-right' aria-hidden='true'></i> <?php echo $this->lang->line('f_cobis_flag_rechazado'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["f_cobis_flag_rechazado_detalle"]; ?>
                                    </td>
                                </tr>
                            
                            <?php
                            }
                            ?>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class='fa fa-angle-double-right' aria-hidden='true'></i> <?php echo $this->lang->line('f_cobis_tracking'); ?>
                                </td>

                                <td style="width: 70%;">
                                    
                                    <span style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaOnboarding('cobis_tracking');">
                                        <i class='fa fa-eye' aria-hidden='true'></i> Ver/Ocultar
                                    </span>
                                    
                                    <div style="display: none;" id="cobis_tracking">
                                        <div id="divTrackingFlow">
                                            <div style="text-align: right; width: 95%; font-style: italic;">
                                                <?php echo ($arrRespuesta[0]["f_cobis_flujo"]==1 ? '(Actualmente en el flujo) &nbsp;&nbsp;' : ''); ?>
                                                <span style="font-weight: bold;" class="EnlaceSimple" onclick="RefreshTrackingFlujoCOBIS(<?php echo $arrRespuesta[0]['terceros_id']; ?>);">
                                                    <i class="fa fa-refresh" aria-hidden="true"></i> Actualizar
                                                </span>
                                            </div>
                                            <?php echo $this->mfunciones_generales->getTablaTrackingFlujoCOBIS($arrRespuesta[0]["f_cobis_tracking"]); ?>
                                        </div>
                                    </div>
                                    
                                </td>
                            </tr>
                                
                        <?php
                        }
                        ?>
                            
                    </table>

                </div>
            
                <br />
            
                <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaOnboarding('2');">
                    <i class='fa fa-eye' aria-hidden='true'></i> Validación de Reconocimiento Facial
                </div>
            
                <div style="display: none;" id="2">
                    
                    <?php $strClase = "FilaGris"; ?>
                    
                    <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('terceros_rekognition'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["terceros_rekognition"], 'si_no') . ($arrRespuesta[0]["tercero_asistencia"]==1 ? ' Corresponde (validación asistida)' : ''); ?>
                            </td>
                        </tr>

                        <?php
                        if($arrRespuesta[0]["terceros_rekognition"] == 1)
                        {
                        ?>

                        <?php
                        
                            $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
                            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);

                            $imagen1 = $this->mfunciones_generales->GetInfoTercerosDigitalizado($arrRespuesta[0]["terceros_id"], $arrConf[0]['conf_doc_validacion1'], 'documento');
                            $imagen2 = $this->mfunciones_generales->GetInfoTercerosDigitalizado($arrRespuesta[0]["terceros_id"], $arrConf[0]['conf_doc_validacion2'], 'documento');

                            $imagen1_nombre = $this->mfunciones_generales->GetDocumentoNombre($arrConf[0]['conf_doc_validacion1']);
                            $imagen2_nombre = $this->mfunciones_generales->GetDocumentoNombre($arrConf[0]['conf_doc_validacion2']);
                            
                            if($imagen1 != FALSE && $imagen2 != FALSE)
                            {
                            ?>
                            
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td colspan="2" style="width: 100%; font-weight: bold;">
                                        
                                        <div style="padding-top: 10px; height: auto;" class="Botones2Opciones">
                                            <?php echo '<img style="border: 7px solid #fff; max-height: 100px; box-shadow: 0 4px 6px 0 rgb(0 0 0 / 20%), 0 4px 10px 0 rgb(0 0 0 / 19%); margin-bottom: 5px;" src="' . $imagen1 . '" /> <br />' . $imagen1_nombre; ?>
                                        </div>
                                        
                                        <div style="padding-top: 10px; height: auto;" class="Botones2Opciones">
                                            <?php echo '<img style="border: 7px solid #fff; max-height: 100px; box-shadow: 0 4px 6px 0 rgb(0 0 0 / 20%), 0 4px 10px 0 rgb(0 0 0 / 19%); margin-bottom: 5px;" src="' . $imagen2 . '" /> <br />' . $imagen2_nombre; ?>
                                        </div>
                                        
                                        <div style="clear: both"></div>
                                        
                                    </td>
                                </tr>
                            
                        <?php
                        
                            }
                        
                        }
                        ?>
                        
                    </table>
                    
                </div>
                
                <br />
            
                <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaOnboarding('3');">
                    <i class='fa fa-eye' aria-hidden='true'></i> Informacion del Lead
                </div>
            
                <div style="display: none;" id="3">
                    
                    <?php $strClase = "FilaBlanca"; ?>
                    
                    <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> DATOS DE IDENTIFICACIÓN </td> </tr>
                        
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('monto_inicial'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['monto_inicial']; ?></td></tr>

                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('direccion_email'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['direccion_email']; ?></td></tr>
                        
                        <?php
                        if($arrRespuesta[0]["tercero_asistencia"] == 0)
                        {
                        ?>
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('coordenadas_geo_dom'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php
                                    if(!preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $arrRespuesta[0]["coordenadas_geo_dom"]))
                                    {
                                        echo "Geolocalización No Registrada";
                                    }
                                    else
                                    {
                                    ?>
                                        <a href="https://maps.google.com/?q=<?php echo $arrRespuesta[0]["coordenadas_geo_dom"]; ?>" target="_blank"> <i class="fa fa-map-marker" aria-hidden="true"></i> Ver Mapa </a>
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('coordenadas_geo_trab'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php
                                    if(!preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $arrRespuesta[0]["coordenadas_geo_trab"]))
                                    {
                                        echo "Geolocalización No Registrada";
                                    }
                                    else
                                    {
                                    ?>
                                        <a href="https://maps.google.com/?q=<?php echo $arrRespuesta[0]["coordenadas_geo_trab"]; ?>" target="_blank"> <i class="fa fa-map-marker" aria-hidden="true"></i> Ver Mapa </a>
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                        
                        <?php
                        }
                        ?>
                            
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('envio'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['envio']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('cI_numeroraiz'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['cI_numeroraiz']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('cI_complemento'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['cI_complemento']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('cI_lugar_emisionoextension'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['cI_lugar_emisionoextension']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('cI_confirmacion_id'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['cI_confirmacion_id']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('di_fecha_vencimiento'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['di_fecha_vencimiento']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('di_indefinido'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['di_indefinido']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('di_primernombre'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['di_primernombre']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('di_segundo_otrosnombres'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['di_segundo_otrosnombres']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('di_primerapellido'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['di_primerapellido']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('di_segundoapellido'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['di_segundoapellido']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('di_fecha_nacimiento'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['di_fecha_nacimiento']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('di_genero'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['di_genero']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('di_estadocivil'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['di_estadocivil']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('di_apellido_casada'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['di_apellido_casada']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dir_tipo_telefono'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dir_tipo_telefono']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dir_notelefonico'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dir_notelefonico']; ?></td></tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> DATOS DEMOGRÁFICOS </td> </tr>
                        
                        
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dd_profesion'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dd_profesion']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dd_nivel_estudios'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dd_nivel_estudios']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dd_dependientes'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dd_dependientes']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dd_proposito_rel_comercial'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dd_proposito_rel_comercial']; ?></td></tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> DATOS ECONÓMICOS </td> </tr>
                        
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dec_ingresos_mensuales'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dec_ingresos_mensuales']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dec_nivel_egresos'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dec_nivel_egresos']; ?></td></tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> DIRECCIÓN </td> </tr>
                        
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dir_tipo_direccion'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dir_tipo_direccion']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dir_departamento'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dir_departamento']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dir_provincia'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dir_provincia']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dir_localidad_ciudad'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dir_localidad_ciudad']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dir_barrio_zona_uv'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dir_barrio_zona_uv']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dir_ubicacionreferencial'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dir_ubicacionreferencial']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dir_av_calle_pasaje'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dir_av_calle_pasaje']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dir_edif_cond_urb'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dir_edif_cond_urb']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dir_numero'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dir_numero']; ?></td></tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> ACTIVIDAD ECONÓMICA </td> </tr>
                        
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('ae_sector_economico'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['ae_sector_economico']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('ae_actividad_fie'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['ae_actividad_fie']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('ae_ambiente'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['ae_ambiente']; ?></td></tr>
                        
                        
                        
                        
                        <?php
                        if($arrRespuesta[0]['ae_sector_economico_codigo'] == 'I' || $arrRespuesta[0]['ae_sector_economico_codigo'] == 'II' || $arrRespuesta[0]['ae_sector_economico_codigo'] == 'III' || $arrRespuesta[0]['ae_sector_economico_codigo'] == 'IV')
                        {
                        ?>
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> LUGAR DONDE REALIZA LA ACTIVIDAD ECONÓMICA </td> </tr>

                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dir_departamento'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dir_departamento_neg']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dir_provincia'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dir_provincia_neg']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dir_localidad_ciudad'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dir_localidad_ciudad_neg']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dir_barrio_zona_uv'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dir_barrio_zona_uv_neg']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dir_av_calle_pasaje'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dir_av_calle_pasaje_neg']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dir_edif_cond_urb'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dir_edif_cond_urb_neg']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('dir_numero'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['dir_numero_neg']; ?></td></tr>
                        
                        <?php
                        }
                        else
                        {
                        ?>
                        
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> LUGAR DE TRABAJO </td> </tr>

                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('emp_nombre_empresa'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['emp_nombre_empresa']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('emp_direccion_trabajo'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['emp_direccion_trabajo']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('emp_telefono_faxtrabaj'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['emp_telefono_faxtrabaj']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('emp_tipo_empresa'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['emp_tipo_empresa']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('emp_antiguedad_empresa'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['emp_antiguedad_empresa']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('emp_codigo_actividad'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['emp_codigo_actividad']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('emp_descripcion_cargo'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['emp_descripcion_cargo']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('emp_fecha_ingreso'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['emp_fecha_ingreso']; ?></td></tr>
                        
                        <?php
                        }
                        ?>
                            
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> REFERENCIAS PERSONALES </td> </tr>
                        
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('rp_nombres'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['rp_nombres']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('rp_primer_apellido'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['rp_primer_apellido']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('rp_segundo_apellido'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['rp_segundo_apellido']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('rp_direccion'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['rp_direccion']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('rp_notelefonicos'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['rp_notelefonicos']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('rp_nexo_cliente'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['rp_nexo_cliente']; ?></td></tr>
                        
                        <?php
                        if($arrRespuesta[0]['di_estadocivil_codigo'] == 'CA')
                        {
                        ?>
                        
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> CÓNYUGE </td> </tr>

                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('con_primer_nombre'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['con_primer_nombre']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('con_segundo_nombre'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['con_segundo_nombre']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('con_primera_pellido'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['con_primera_pellido']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('con_segundoa_pellido'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['con_segundoa_pellido']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('con_acteconomica_principal'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['con_acteconomica_principal']; ?></td></tr>
                        
                        <?php
                        }
                        ?>
                            
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> DECLARACION DE CIUDADANIA </td> </tr>
                        
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('ddc_ciudadania_usa'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['ddc_ciudadania_usa']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('ddc_motivo_permanencia_usa'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['ddc_motivo_permanencia_usa']; ?></td></tr>
                        
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