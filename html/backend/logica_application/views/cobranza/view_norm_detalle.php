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
                    Ajax_CargadoGeneralPagina("Usuario/Detalle", "divElementoFlotante", "divErrorBusqueda", "", strParametros);
                }
            ';
    }
    ?>

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
                    <i class='fa fa-eye' aria-hidden='true'></i> Informacion del Cliente
                </div>
            
                <div id="1">
                    
                    <?php $strClase = "FilaBlanca"; ?>
                    <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('norm_id'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->lang->line('norm_prefijo') . $arrRespuesta[0]["norm_id"]; ?>
                            </td>
                        </tr>

                        <?php
                        if(1 == 1)
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
                                <?php echo $arrRespuesta[0]["norm_fecha"]; ?>
                            </td>
                        </tr>

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('terceros_columna7'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["norm_estado"]; ?>
                            </td>
                        </tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('norm_finalizacion'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["norm_finalizacion"]; ?>
                            </td>
                        </tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('cv_fecha_ultima'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["cv_fecha"]; ?>
                            </td>
                        </tr>
                        
                        <?php
                        if($arrRespuesta[0]["cv_fecha_check"] != FALSE)
                        {
                        ?>
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    | <?php echo $this->lang->line('cv_fecha_compromiso'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["cv_fecha_compromiso"] . ($arrRespuesta[0]['cv_fecha_compromiso_check'] ? ' ' . $this->lang->line('norm_reporte_vencido_error') : ''); ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    | <?php echo $this->lang->line('cv_resultado'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["cv_resultado"]; ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        
                    </table>
                    <br />
                    
                    <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> DATOS PERSONALES </td> </tr>
                        
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('registro_num_proceso'); ?></td><td style='width: 70%;'><?php echo ((int)$arrRespuesta[0]["registro_num_proceso"] <=0 ? $this->lang->line('prospecto_no_evaluacion') : $arrRespuesta[0]["registro_num_proceso"]); ?></td></tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                Agencia Asociada
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->ObtenerNombreRegionCodigo($arrRespuesta[0]["codigo_agencia_fie"]); ?>
                            </td>
                        </tr>
                        
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('norm_primer_nombre'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['norm_primer_nombre']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('norm_segundo_nombre'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['norm_segundo_nombre']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('norm_primer_apellido'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['norm_primer_apellido']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('norm_segundo_apellido'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['norm_segundo_apellido']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('norm_cel'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['norm_cel']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('norm_actividad'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['norm_actividad']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('norm_rel_cred'); ?></td><td style='width: 70%;'><?php echo ($arrRespuesta[0]['norm_rel_cred_codigo']==99 ? 'Otro: ' . $arrRespuesta[0]['norm_rel_cred_otro'] : $arrRespuesta[0]['norm_rel_cred']); ?></td></tr>
                        
                    </table>
                    
                </div>
            
                <br />
                
                <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaOnboarding('2');">
                    <i class='fa fa-eye' aria-hidden='true'></i> Direcciones Registradas
                </div>
            
                <div style="display: none;" id="2">
                    
                    <?php
                    
                    if(count($arrDirecciones[0]) <= 0)
                    {
                        echo $this->lang->line('TablaNoResultados_seccion');
                    }
                    else
                    {
                        $cont_1 = 0;
                        
                        foreach ($arrDirecciones as $key => $value_direccion) {
                        
                            $cont_1++;
                        ?>
                    
                            <br />
                            
                            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                                <?php $strClase = "FilaBlanca"; ?>
                                
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> <?php echo 'DIRECCIÓN #' . $cont_1; ?> </td> </tr>
                                
                                <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('rd_tipo'); ?></td><td style='width: 70%;'><?php echo $value_direccion['rd_tipo']; ?></td></tr>
                                <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('rd_dir_departamento'); ?></td><td style='width: 70%;'><?php echo $value_direccion['rd_dir_departamento']; ?></td></tr>
                                <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('rd_dir_provincia'); ?></td><td style='width: 70%;'><?php echo $value_direccion['rd_dir_provincia']; ?></td></tr>
                                <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('rd_dir_localidad_ciudad'); ?></td><td style='width: 70%;'><?php echo $value_direccion['rd_dir_localidad_ciudad']; ?></td></tr>
                                <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('rd_cod_barrio'); ?></td><td style='width: 70%;'><?php echo $value_direccion['rd_cod_barrio']; ?></td></tr>
                                <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('rd_direccion'); ?></td><td style='width: 70%;'><?php echo $value_direccion['rd_direccion']; ?></td></tr>
                                <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('rd_edificio'); ?></td><td style='width: 70%;'><?php echo $value_direccion['rd_edificio']; ?></td></tr>
                                <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('rd_numero'); ?></td><td style='width: 70%;'><?php echo $value_direccion['rd_numero']; ?></td></tr>

                                <?php
                                // Tipo dirección       1=Negocio ; 2=Domicilio
                                if((int)$value_direccion['rd_tipo_codigo'] == 1)
                                {
                                ?>
                                    <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_trabajo_lugar'); ?></td><td style='width: 70%;'><?php echo ($value_direccion['rd_trabajo_lugar_codigo']==99 ? 'Otro: ' . $value_direccion['rd_trabajo_lugar_otro'] : $value_direccion['rd_trabajo_lugar']); ?></td></tr>
                                    <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_trabajo_realiza'); ?></td><td style='width: 70%;'><?php echo ($value_direccion['rd_trabajo_realiza_codigo']==99 ? 'Otro: ' . $value_direccion['rd_trabajo_realiza_otro'] : $value_direccion['rd_trabajo_realiza']); ?></td></tr>
                                <?php
                                }
                                else
                                {
                                ?>
                                    <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_dom_tipo'); ?></td><td style='width: 70%;'><?php echo ($value_direccion['rd_dom_tipo_codigo']==99 ? 'Otro: ' . $value_direccion['rd_dom_tipo_otro'] : $value_direccion['rd_dom_tipo']); ?></td></tr>
                                <?php
                                }
                                ?>
                                
                                <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('rd_referencia_texto'); ?></td><td style='width: 70%;'><?php echo $value_direccion['rd_referencia_texto']; ?></td></tr>
                                    
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> <?php echo ($value_direccion['rd_referencia_codigo']==0 ? 'Sin ' : ''); ?> Referencia de la dirección </td> </tr>
                                    
                                <?php
                                if($value_direccion['rd_referencia_codigo'] == 1)
                                {
                                ?>
                                    <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('rd_geolocalizacion'); ?></td>

                                        <td style="width: 70%;">
                                            <?php
                                            if(!preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $value_direccion["rd_geolocalizacion"]))
                                            {
                                                echo "Geolocalización No Registrada";
                                            }
                                            else
                                            {
                                            ?>
                                                <a href="https://maps.google.com/?q=<?php echo $value_direccion["rd_geolocalizacion"]; ?>" target="_blank"> <i class="fa fa-map-marker" aria-hidden="true"></i> <strong>Ver Mapa</strong> </a>
                                            <?php
                                            }
                                            ?>
                                        </td>

                                    </tr>
                                <?php
                                }
                                ?>

                                <?php
                                if($value_direccion['rd_referencia_codigo'] == 2)
                                {
                                    if($value_direccion['rd_croquis'] == '')
                                    {
                                        $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; text-align: center;"> Croquis seleccionado pero no registrado </td> </tr>
                                    <?php
                                    }
                                    else
                                    {
                                ?>
                                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> 
                                        <tr class='<?php echo $strClase; ?>'>
                                            <td style='width: 30%; font-weight: bold;'>
                                                <?php echo $this->lang->line('rd_croquis'); ?>
                                            </td>
                                            <td style='width: 70%;'>
                                                <span class="EnlaceSimple" onclick="VerTablaOnboarding('rd_croquis<?php echo $cont_1; ?>');"> <i class="fa fa-map-o" aria-hidden="true"></i> <strong>Ver/Ocultar Imagen Croquis</strong> </span>
                                                <div style="display: none; text-align: center;" id="rd_croquis<?php echo $cont_1; ?>">
                                                    <br />
                                                    <img src="<?php echo $value_direccion['rd_croquis']; ?>" style="max-width: 96%; background-color: #ffffff; border: 7px solid #ffffff; box-shadow: 0 4px 6px 0 rgb(0 0 0 / 20%), 0 4px 10px 0 rgb(0 0 0 / 19%); margin-bottom: 5px;" />
                                                </div>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                                
                            </table>
                        <?php
                        }
                    }
                    
                    ?>
                    
                </div>
                
                <br />
                
                <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaOnboarding('3');">
                    <i class='fa fa-eye' aria-hidden='true'></i> Visitas Registradas
                </div>
            
                <div style="display: none; overflow-x: auto;" id="3">
                    
                    <?php
                    
                    if(count($arrVisita[0]) <= 0)
                    {
                        echo $this->lang->line('TablaNoResultados_seccion');
                    }
                    else
                    {
                    ?>
                        <br />
                        
                        <table class="tablaresultados Mayuscula" style="width: 100%; background-color: #f0f0f0; font-size: 10px;" border="0">
                            <tr class="FilaGris">
                                <td style="width: 3%; font-size: 10px; font-weight: bold; text-align: center;">
                                    #
                                </td>
                                <td style="width: 10%; font-size: 10px; font-weight: bold; text-align: center;">
                                    <?php echo $this->lang->line('terceros_columna8'); ?>
                                </td>
                                <td style="width: 25%; font-size: 10px; font-weight: bold; text-align: center;">
                                    <?php echo $this->lang->line('cv_resultado'); ?>
                                </td>
                                <td style="width: 12%; font-size: 10px; font-weight: bold; text-align: center;">
                                    <?php echo $this->lang->line('cv_fecha_compromiso'); ?>
                                </td>
                                <td style="width: 25%; font-size: 10px; font-weight: bold; text-align: center;">
                                    <?php echo $this->lang->line('cv_observaciones'); ?>
                                </td>
                                <td style="width: 25%; font-size: 10px; font-weight: bold; text-align: center;">
                                    <?php echo $this->lang->line('cv_checkin'); ?>
                                </td>
                            </tr>
                    <?php  
                        $cont_1 = 0;
                        
                        foreach ($arrVisita as $key => $value_visita) {
                        
                            $cont_1++;
                        ?>
                    
                            <tr class="FilaBlanca">
                                
                                <td style="text-align: center;">
                                    <?php echo $cont_1; ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php echo $value_visita['cv_fecha']; ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php echo $value_visita['cv_resultado']; ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php echo $value_visita['cv_fecha_compromiso']; ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php echo $value_visita['cv_observaciones']; ?>
                                </td>
                                <td style="text-align: center;">
                                    
                                    <?php
                                    if($value_visita['cv_checkin'] == 1)
                                    {
                                    echo '
                                        <div class="col-sm-2">Si</div>
                                        <div class="col-sm-4">' . $value_visita['cv_checkin_fecha'] . '</div>
                                        <div class="col-sm-3"> <a href="https://maps.google.com/?q=' . $value_visita['cv_checkin_geo'] . '" target="_blank"> <i class="fa fa-map-marker" aria-hidden="true"></i> <strong>Ver Mapa</strong> </a> </div>
                                        ';
                                    }
                                    else
                                    {
                                        echo 'No registrado';
                                    }
                                    ?>
                                    
                                </td>
                                
                            </tr>
                    <?php
                        }
                    ?>
                        </table>
                    <?php
                    }
                    ?>
                                
                </div>
                
                <br />
                
                <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaOnboarding('4');">
                    <i class='fa fa-eye' aria-hidden='true'></i> Información del Registro
                </div>
            
                <div style="display: none;" id="4">
            
                    <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                        <?php $strClase = "FilaBlanca"; ?>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->mfunciones_microcreditos->GetValorCatalogo(2, 'solicitud_estado'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["norm_consolidado"], 'si_no'); ?>
                            </td>
                        </tr>

                        <?php
                        if($arrRespuesta[0]["norm_consolidado"] == 1)
                        {
                        ?>
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?php echo $this->mfunciones_microcreditos->GetValorCatalogo(2, 'solicitud_estado') . ' - Fecha'; ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["norm_consolidado_fecha"]; ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?php echo $this->mfunciones_microcreditos->GetValorCatalogo(2, 'solicitud_estado') . ' - Usuario'; ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->getNombreUsuario($arrRespuesta[0]["norm_consolidado_usuario"]); ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i> <i class="fa fa-clock-o" aria-hidden="true"></i> Flujo Concluido
                                </td>

                                <td style="width: 70%;">
                                    <?php
                                    
                                    $tiempo_total = $this->mfunciones_generales->GetTotalHorasFlujo(7);
                                    
                                    $tiempo = $this->mfunciones_generales->ObtenerHorasLaboral($arrRespuesta[0]["norm_fecha_plano"], $arrRespuesta[0]["norm_consolidado_fecha_plano"], $tiempo_total);
                                    
                                    echo 'Horas laborales: ' . $tiempo->horas_laborales . ' | ' . $tiempo->resultado_texto;
                                    
                                    ?>
                                </td>
                            </tr>
                            
                        <?php
                        }
                        ?>
                            
                        <?php
                        if((int)$arrRespuesta[0]["norm_observado_app"] == 1)
                        {
                        ?>
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('norm_observado_app'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["norm_observado_app"], 'si_no') . '<br />' . $this->lang->line('norm_observado_app_ayuda'); ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        
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