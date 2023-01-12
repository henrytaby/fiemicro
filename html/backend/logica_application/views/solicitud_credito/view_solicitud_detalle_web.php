<script type="text/javascript">

    function VerTablaOnboarding(id) {

        $("#"+id).slideToggle();
    }
    
    function Ajax_CargarAccion_DetalleSolicitudCred(codigo, vista) {
        var strParametros = "&codigo=" + codigo + "&vista=" + vista;
        Ajax_CargadoGeneralPagina('SolWeb/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
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

    function Visor_SolFormPDF(codigo_registro, tipo)
    {
        $.ajax({
            url: 'Registros/SolCred/OTAFormulario',
            type: 'post',
            data: {
                estructura_id:codigo_registro, codigo_ejecutivo:2, tipo_registro:tipo
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                if(len>0)
                {
                    window.open('<?php echo $this->config->base_url(); ?>Registros/SolCred/PrepForm/?' + response[0]['armado'],'_blank');
                }
                else
                {
                    $('#divErrorBusqueda').html('<br />No se generó el formulario, por favor revise el registro e inténtelo nuevamente.<br /><br />');
                    $('html,body').scrollTop(0);
                }
            }
        });
    }

</script>

    <div style="overflow-y: auto; height: 400px;">
    
        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('DetalleRegistroTitulo'); ?> (Resumen)</div>
        
        <div style="clear: both"></div>

        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php

            if(count($arrRespuesta[0]) > 0)
            {

            ?>
                <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaOnboarding('1');">
                    <i class='fa fa-eye' aria-hidden='true'></i> Informacion de la Solicitud de Crédito
                </div>
            
                <div id="1">
                    
                    <?php $strClase = "FilaBlanca"; ?>
                    <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
                        
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'>Vista actual (Resumen)</td><td style='width: 70%;'>Cambiar a <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleSolicitudCred('<?php echo $arrRespuesta[0]["sol_id"]; ?>', 1);"> <strong> Vista Completa <i class="fa fa-external-link" aria-hidden="true"></i> </strong> </span></td></tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('prospecto_id'); ?>
                            </td>

                            <td style="width: 70%;">
                                SOL_<?php echo $arrRespuesta[0]["sol_id"]; ?>
                            </td>
                        </tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                Actividad Principal - <?php echo $this->lang->line('import_campana'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["sol_codigo_rubro"]; ?>
                            </td>
                        </tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('sol_codigo_rubro_sec'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["sol_codigo_rubro_sec"]; ?>
                            </td>
                        </tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('tercero_asistencia'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["sol_asistencia"], 'tercero_asistencia'); ?>
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
                        if($arrRespuesta[0]["sol_asistencia"] == 1)
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
                                <?php echo $arrRespuesta[0]["sol_fecha"]; ?>
                            </td>
                        </tr>

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('terceros_columna7'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $arrRespuesta[0]["sol_estado"]; ?>
                            </td>
                        </tr>
                        
                    </table>
                    <br />
                    
                    <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> DATOS DE IDENTIFICACIÓN </td> </tr>
                        
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_ci'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_ci']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_extension'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_extension']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_complemento'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_complemento']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_primer_nombre'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_primer_nombre']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_segundo_nombre'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_segundo_nombre']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_primer_apellido'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_primer_apellido']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_segundo_apellido'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_segundo_apellido']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_correo'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_correo']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_cel'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_cel']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_telefono'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_telefono']; ?></td></tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> <?php echo $this->mfunciones_microcreditos->GetValorCatalogo($arrRespuesta[0]['sol_dependencia'], 'sol_dependencia'); ?> </td> </tr>
                        
                        <?php
                        if($arrRespuesta[0]['sol_dependencia'] == 2)
                        {
                        ?>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_indepen_actividad'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_indepen_actividad']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_indepen_telefono'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_indepen_telefono']; ?></td></tr>
                        <?php
                        }
                        ?>
                        
                        <?php
                        if($arrRespuesta[0]['sol_dependencia'] == 1)
                        {
                        ?>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_depen_empresa'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_depen_empresa']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_depen_cargo'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_depen_cargo']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_depen_telefono'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_depen_telefono']; ?></td></tr>
                        <?php
                        }
                        ?>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> MONTO SOLICITADO </td> </tr>
                            
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_moneda'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_moneda']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_monto'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_monto']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_detalle'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_detalle']; ?></td></tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> DIRECCION DEL NEGOCIO/ACTIVIDAD O LUGAR DE TRABAJO </td> </tr>
                        
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_dir_departamento'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_dir_departamento']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_dir_provincia'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_dir_provincia']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_dir_localidad_ciudad'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_dir_localidad_ciudad']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_cod_barrio'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_cod_barrio']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_direccion_trabajo'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_direccion_trabajo']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_edificio_trabajo'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_edificio_trabajo']; ?></td></tr>
                        <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_numero_trabajo'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_numero_trabajo']; ?></td></tr>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> <?php echo ($arrRespuesta[0]['sol_dir_referencia']==0 ? 'Sin ' : ''); ?> Referencia de la dirección </td> </tr>
                        
                        <?php
                        if($arrRespuesta[0]['sol_dir_referencia'] == 1)
                        {
                        ?>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_geolocalizacion'); ?></td>
                            
                                <td style="width: 70%;">
                                    <?php
                                    if(!preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $arrRespuesta[0]["sol_geolocalizacion"]))
                                    {
                                        echo "Geolocalización No Registrada";
                                    }
                                    else
                                    {
                                    ?>
                                        <a href="https://maps.google.com/?q=<?php echo $arrRespuesta[0]["sol_geolocalizacion"]; ?>" target="_blank"> <i class="fa fa-map-marker" aria-hidden="true"></i> <strong>Ver Mapa</strong> </a>
                                    <?php
                                    }
                                    ?>
                                </td>
                            
                            </tr>
                        <?php
                        }
                        ?>
                        
                        <?php
                        if($arrRespuesta[0]['sol_dir_referencia'] == 2)
                        {
                            if($arrRespuesta[0]['sol_croquis'] == '')
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
                                        <?php echo $this->lang->line('sol_croquis'); ?>
                                    </td>
                                    <td style='width: 70%;'>
                                        <span class="EnlaceSimple" onclick="VerTablaOnboarding('sol_croquis');"> <i class="fa fa-map-o" aria-hidden="true"></i> <strong>Ver/Ocultar Imagen Croquis</strong> </span>
                                        <div style="display: none; text-align: center;" id="sol_croquis">
                                            <br />
                                            <img src="<?php echo $arrRespuesta[0]['sol_croquis']; ?>" style="max-width: 96%; background-color: #ffffff; border: 7px solid #ffffff; box-shadow: 0 4px 6px 0 rgb(0 0 0 / 20%), 0 4px 10px 0 rgb(0 0 0 / 19%); margin-bottom: 5px;" />
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                        
                        <?php
                        if($arrRespuesta[0]['sol_conyugue'] == 1)
                        {
                        ?>
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> CÓNYUGE - DATOS DE IDENTIFICACIÓN </td> </tr>

                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_ci'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_ci']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_extension'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_extension']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_complemento'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_complemento']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_primer_nombre'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_primer_nombre']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_segundo_nombre'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_segundo_nombre']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_primer_apellido'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_primer_apellido']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_segundo_apellido'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_segundo_apellido']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_correo'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_correo']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_cel'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_cel']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_telefono'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_telefono']; ?></td></tr>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> CÓNYUGE - <?php echo $this->mfunciones_microcreditos->GetValorCatalogo($arrRespuesta[0]['sol_con_dependencia'], 'sol_dependencia'); ?> </td> </tr>

                            <?php
                            if($arrRespuesta[0]['sol_con_dependencia'] == 2)
                            {
                            ?>
                                <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_indepen_actividad'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_indepen_actividad']; ?></td></tr>
                                <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_indepen_telefono'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_indepen_telefono']; ?></td></tr>
                            <?php
                            }
                            ?>

                            <?php
                            if($arrRespuesta[0]['sol_con_dependencia'] == 1)
                            {
                            ?>
                                <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_depen_empresa'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_depen_empresa']; ?></td></tr>
                                <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_depen_cargo'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_depen_cargo']; ?></td></tr>
                                <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_depen_telefono'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_depen_telefono']; ?></td></tr>
                            <?php
                            }
                            ?>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> CÓNYUGE - DIRECCION DEL NEGOCIO/ACTIVIDAD O LUGAR DE TRABAJO </td> </tr>

                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_dir_departamento'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_dir_departamento']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_dir_provincia'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_dir_provincia']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_dir_localidad_ciudad'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_dir_localidad_ciudad']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_cod_barrio'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_cod_barrio']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_direccion_trabajo'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_direccion_trabajo']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_edificio_trabajo'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_edificio_trabajo']; ?></td></tr>
                            <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_numero_trabajo'); ?></td><td style='width: 70%;'><?php echo $arrRespuesta[0]['sol_con_numero_trabajo']; ?></td></tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> <tr class="<?php echo $strClase; ?>"> <td colspan="2" style="width: 100%; font-weight: bold; font-size: 1.3em; text-align: center;"> CÓNYUGE - <?php echo ($arrRespuesta[0]['sol_con_dir_referencia']==0 ? 'Sin ' : ''); ?> Referencia de la dirección </td> </tr>
                        
                            <?php
                            if($arrRespuesta[0]['sol_con_dir_referencia'] == 1)
                            {
                            ?>
                                <?php $strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 30%; font-weight: bold;'><?php echo $this->lang->line('sol_con_geolocalizacion'); ?></td>
                                
                                    <td style="width: 70%;">
                                        <?php
                                        if(!preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $arrRespuesta[0]["sol_con_geolocalizacion"]))
                                        {
                                            echo "Geolocalización No Registrada";
                                        }
                                        else
                                        {
                                        ?>
                                            <a href="https://maps.google.com/?q=<?php echo $arrRespuesta[0]["sol_con_geolocalizacion"]; ?>" target="_blank"> <i class="fa fa-map-marker" aria-hidden="true"></i> <strong>Ver Mapa</strong> </a>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                
                                </tr>
                            <?php
                            }
                            ?>

                            <?php
                            if($arrRespuesta[0]['sol_con_dir_referencia'] == 2)
                            {
                                if($arrRespuesta[0]['sol_con_croquis'] == '')
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
                                            <?php echo $this->lang->line('sol_con_croquis'); ?>
                                        </td>
                                        <td style='width: 70%;'>
                                            <span class="EnlaceSimple" onclick="VerTablaOnboarding('sol_con_croquis');"> <i class="fa fa-map-o" aria-hidden="true"></i> <strong>Ver/Ocultar Imagen Croquis</strong> </span>
                                            <div style="display: none; text-align: center;" id="sol_con_croquis">
                                                <br />
                                                <img src="<?php echo $arrRespuesta[0]['sol_con_croquis']; ?>" style="max-width: 96%; background-color: #ffffff; border: 7px solid #ffffff; box-shadow: 0 4px 6px 0 rgb(0 0 0 / 20%), 0 4px 10px 0 rgb(0 0 0 / 19%); margin-bottom: 5px;" />
                                            </div>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        
                        <?php
                        }
                        ?>
                            
                    </table>
                    
                </div>
            
                <br />
            
                <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaOnboarding('2');">
                    <i class='fa fa-eye' aria-hidden='true'></i> Formulario Solicitud de Crédito
                </div>
            
                <div style="display: none;" id="2">
                    
                    <?php $strClase = "FilaGris"; ?>
                    <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('sol_form_generar'); ?>
                                <span style="text-shadow: 0 0 black;" class="AyudaTooltip" data-balloon-length="medium" data-balloon="<?php echo $this->lang->line('sol_info_formulario'); ?>" data-balloon-pos="right"> </span>
                            </td>

                            <td style="width: 70%;">
                                <?php
                                    $ua = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
                                    if (preg_match('~MSIE|Internet Explorer~i', $ua) || (strpos($ua, 'Trident/7.0') !== false && strpos($ua, 'rv:11.0') !== false))
                                    {
                                        echo "<div class='label-campo'> <i class='fa fa-meh-o' aria-hidden='true'></i> Lo sentimos, tu explorador no soporta esta funcionalidad.<br />Por favor ingresa con un explorador actualizado (Chrome, Firefox, Safari, Opera, etc.). </div>";
                                    }
                                    else
                                    {
                                ?>
                                        <span style="float: left !important; margin-right: 10px;" class="EnlaceSimple" onclick="Visor_SolFormPDF('<?php echo $arrRespuesta[0]['sol_id']; ?>', 'pdf')">
                                            <strong><i class="fa fa-eye" aria-hidden="true"></i> Generar PDF</strong>
                                        </span>
                                <?php
                                    }
                                ?>
                            </td>
                        </tr>
                        
                    </table>
                    
                </div>
            
                <br />
                
                <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaOnboarding('3');">
                    <i class='fa fa-eye' aria-hidden='true'></i> Información del Registro
                </div>
            
                <div style="display: none;" id="3">
            
                    <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                        <?php $strClase = "FilaGris"; ?>

                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->mfunciones_microcreditos->GetValorCatalogo(1, 'solicitud_estado'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["sol_asignado"], 'si_no'); ?>
                            </td>
                        </tr>

                        <?php
                        if($arrRespuesta[0]["sol_asignado"] == 1)
                        {
                        ?>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?php echo $this->mfunciones_microcreditos->GetValorCatalogo(1, 'solicitud_estado') . ' - Fecha'; ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["sol_asignado_fecha"]; ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?php echo $this->mfunciones_microcreditos->GetValorCatalogo(1, 'solicitud_estado') . ' - Usuario'; ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->getNombreUsuario($arrRespuesta[0]["sol_asignado_usuario"]); ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?php echo $this->lang->line('import_agente'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["agente_nombre"]; ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <?php echo $this->lang->line('prospecto_checkin'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["sol_checkin"], 'si_no'); ?>
                                </td>
                            </tr>

                            <?php
                            if($arrRespuesta[0]["sol_checkin"] > 0)
                            {
                            ?>

                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?php echo $this->lang->line('prospecto_checkin_fecha'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["sol_checkin_fecha"]; ?>
                                    </td>
                                </tr>
                                
                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?php echo $this->lang->line('prospecto_checkin_geo'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <a href="https://maps.google.com/?q=<?php echo $arrRespuesta[0]["sol_checkin_geo"]; ?>" target="_blank"> <i class="fa fa-map-marker" aria-hidden="true"></i> Ver Mapa </a>
                                    </td>
                                </tr>

                            <?php
                            }
                            ?>
                            
                            
                        <?php
                        }
                        ?>
                        
                        <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->mfunciones_microcreditos->GetValorCatalogo(2, 'solicitud_estado'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["sol_consolidado"], 'si_no'); ?>
                            </td>
                        </tr>

                        <?php
                        if($arrRespuesta[0]["sol_consolidado"] == 1)
                        {
                        ?>
                        
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?php echo $this->mfunciones_microcreditos->GetValorCatalogo(2, 'solicitud_estado') . ' - Evaluación'; ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["sol_evaluacion"]; ?>
                                </td>
                            </tr>
                        
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?php echo $this->mfunciones_microcreditos->GetValorCatalogo(2, 'solicitud_estado') . ' - Convertido a Estudio'; ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php
                                    
                                        if((int)$arrRespuesta[0]["sol_estudio"] == 1 && (int)$arrRespuesta[0]["sol_trabajo_actividad_pertenece"] > 0)
                                        {
                                            echo "Si - " . PREFIJO_PROSPECTO . $arrRespuesta[0]["sol_estudio_codigo"];
                                            echo "<br /><i class='fa fa-angle-double-right' aria-hidden='true'></i> " . $this->mfunciones_microcreditos->GetValorCatalogo($arrRespuesta[0]['sol_estudio_actividad'], 'estudio_actividad');
                                            echo "<br /><i class='fa fa-angle-double-right' aria-hidden='true'></i> Solicitante a Titular, rubro: " . $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]['sol_trabajo_actividad_pertenece'], 'nombre_rubro');
                                            
                                            if((int)$arrRespuesta[0]["sol_con_trabajo_actividad_pertenece"] > 0)
                                            {
                                                echo "<br /><i class='fa fa-angle-double-right' aria-hidden='true'></i> Cónyuge a U. Familiar, rubro: " . $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]['sol_con_trabajo_actividad_pertenece'], 'nombre_rubro');
                                            }
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
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?php echo $this->mfunciones_microcreditos->GetValorCatalogo(2, 'solicitud_estado') . ' - Fecha'; ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["sol_consolidado_fecha"]; ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?php echo $this->mfunciones_microcreditos->GetValorCatalogo(2, 'solicitud_estado') . ' - Usuario'; ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->getNombreUsuario($arrRespuesta[0]["sol_consolidado_usuario"]); ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i> <i class="fa fa-clock-o" aria-hidden="true"></i> Flujo Concluido
                                </td>

                                <td style="width: 70%;">
                                    <?php
                                    
                                    $tiempo_total = $this->mfunciones_generales->GetTotalHorasFlujo(6);
                                    
                                    $tiempo = $this->mfunciones_generales->ObtenerHorasLaboral($arrRespuesta[0]["sol_fecha_plano"], $arrRespuesta[0]["sol_consolidado_fecha_plano"], $tiempo_total);
                                    
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
                                <?php echo $this->mfunciones_microcreditos->GetValorCatalogo(3, 'solicitud_estado'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["sol_rechazado"], 'si_no'); ?>
                            </td>
                        </tr>

                        <?php
                        if($arrRespuesta[0]["sol_rechazado"] == 1)
                        {
                        ?>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?php echo $this->mfunciones_microcreditos->GetValorCatalogo(3, 'solicitud_estado') . ' - Fecha'; ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $arrRespuesta[0]["sol_rechazado_fecha"]; ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?php echo $this->mfunciones_microcreditos->GetValorCatalogo(3, 'solicitud_estado') . ' - Usuario'; ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->getNombreUsuario($arrRespuesta[0]["sol_rechazado_usuario"]); ?>
                                </td>
                            </tr>
                            
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?php echo $this->mfunciones_microcreditos->GetValorCatalogo(3, 'solicitud_estado') . ' - Texto'; ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo nl2br($arrRespuesta[0]["sol_rechazado_texto"]); ?>
                                </td>
                            </tr>
                            
                        <?php
                        }
                        
                        if((int)$arrRespuesta[0]["sol_codigo_rubro_codigo"] >= 7 && (int)$arrRespuesta[0]["sol_codigo_rubro_codigo"] <= 12)
                        {
                        ?>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    Aux - <?php echo $this->lang->line('prospecto_jda_eval'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["sol_jda_eval"], 'prospecto_evaluacion'); ?>
                                </td>
                            </tr>

                            <?php
                            if((int)$arrRespuesta[0]["sol_jda_eval"] == 1 || (int)$arrRespuesta[0]["sol_jda_eval"] == 2)
                            {
                            ?>

                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        Aux - <?php echo $this->lang->line('prospecto_jda_eval_usuario'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["sol_jda_eval_usuario"]; ?>
                                    </td>
                                </tr>

                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        Aux - <?php echo $this->lang->line('prospecto_jda_eval_fecha'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["sol_jda_eval_fecha"]; ?>
                                    </td>
                                </tr>

                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        Aux - <?php echo $this->lang->line('prospecto_jda_eval') . ' - ' . $this->lang->line('prospecto_jda_eval_texto'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["sol_jda_eval_texto"]; ?>
                                    </td>
                                </tr>

                            <?php
                            }
                            ?>

                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    Aux - <?php echo $this->lang->line('registro_num_proceso'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo ((int)$arrRespuesta[0]["registro_num_proceso"] <=0 ? $this->lang->line('prospecto_no_evaluacion') : $arrRespuesta[0]["registro_num_proceso"]); ?>
                                </td>
                            </tr>
                                
                            <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 30%; font-weight: bold;">
                                    Aux - <?php echo $this->lang->line('prospecto_desembolso'); ?>
                                </td>

                                <td style="width: 70%;">
                                    <?php echo $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]["sol_desembolso"], 'si_no'); ?>
                                </td>
                            </tr>

                            <?php
                            if((int)$arrRespuesta[0]["sol_desembolso"] == 1)
                            {
                            ?>

                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        Aux - <?php echo $this->lang->line('prospecto_desembolso_monto'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["sol_desembolso_monto"]; ?>
                                    </td>
                                </tr>

                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        Aux - <?php echo $this->lang->line('prospecto_desembolso_usuario'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["sol_desembolso_usuario"]; ?>
                                    </td>
                                </tr>

                                <?php $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 30%; font-weight: bold;">
                                        Aux - <?php echo $this->lang->line('prospecto_desembolso_fecha'); ?>
                                    </td>

                                    <td style="width: 70%;">
                                        <?php echo $arrRespuesta[0]["sol_desembolso_fecha"]; ?>
                                    </td>
                                </tr>

                        <?php
                            }
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