<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios('sincombo');
    
    if($tipo_registro == 'nuevo_solcredito')
    {
        echo '$(".informacion_operacion").hide();';
    }
    
?>

    $(document).ready(function() {
        
        $("div.modal-backdrop").remove();
        
        $(document).ready(function(){ 
            $('.contenido_formulario').find("input[type=text]").each(function(ev)
            {
                $(this).attr("placeholder", " :: Registrar ::");
            });
            $('.contenido_formulario').find("input[type=tel]").each(function(ev)
            {
                $(this).attr("placeholder", " :: Registrar ::");
            });
        });
        
        $(document).ready(function() {
            $("#sol_codigo_rubro, #sol_cliente, #sol_conyugue, #sol_dependencia, #sol_codigo_rubro_sec, #sol_actividad_secundaria, #sol_dependencia_sec").togglebutton();
            $("div.modal-backdrop").remove();
        });
        
        $('.datos_personales').find("input[type=text]").each(function(ev)
        {
            $(this).attr("style","text-transform: capitalize;");
        });
        
        sol_actvidad();
        sol_actvidad_secundaria();
        sol_actvidad_sec();
    });

    // ### ACTIVIDAD SECUNDARIA  INICIO
    
    function sol_actvidad_secundaria()
    {
        switch (parseInt($("#sol_actividad_secundaria").val())) {
            case 0:
                $("#bloque_secundaria").fadeOut();
                break;
            case 1:
                $("#bloque_secundaria").fadeIn();
                break;
            default:
                
                $("#bloque_secundaria").fadeOut();
                
                break;
        }
    }
    
    $("#sol_actividad_secundaria").on('change', function(){
        sol_actvidad_secundaria();
    });
    
    // ### ACTIVIDAD SECUNDARIA  FIN

    // ### ACTIVIDAD ECONÓMICA  INICIO

    // Solicitante
    function sol_actvidad()
    {
        $(".sol_act_ind, .sol_act_dep").hide();
            
        switch (parseInt($("#sol_dependencia").val())) {
            case 1:
                $(".sol_act_dep").fadeIn();
                break;
            case 2:
                $(".sol_act_ind").fadeIn();
                break;
            default:
                break;
        }
    }

    $("#sol_dependencia").on('change', function(){
        sol_actvidad();
    });
    
    // Actividad Secundaria
    function sol_actvidad_sec()
    {
        $(".sol_act_ind_sec, .sol_act_dep_sec").hide();
            
        switch (parseInt($("#sol_dependencia_sec").val())) {
            case 1:
                $(".sol_act_dep_sec").fadeIn();
                break;
            case 2:
                $(".sol_act_ind_sec").fadeIn();
                break;
            default:
                break;
        }
    }

    $("#sol_dependencia_sec").on('change', function(){
        sol_actvidad_sec();
    });
    
    // ### ACTIVIDAD ECONÓMICA  FIN
    
    function Mostrar_sol_conyugue_marcar()
    {
        $("#sol_conyugue_opcion").hide();
        $("#sol_conyugue_marcar").fadeIn();
    }
    
    function Mostrar_sol_secundaria_marcar()
    {
        $("#sol_secundaria_opcion").hide();
        $("#sol_secundaria_marcar").fadeIn();
    }
    
</script>

    <?php
    
        $text_unidad_familiar = '';
        if($tipo_registro == 'nuevo_solcredito')
        {
            $vista_actual = "0";
            $text_unidad_familiar = 'NUEVA UNIDAD FAMILIAR<br />';
        }
    
        if($vista_actual == '0')
        {
            $clase_contenido_extra = 'contenido_formulario-nopasos';
            $clase_navbar_extra = 'navbar-nopasos';
        }
        else
        {
            $clase_contenido_extra = '';
            $clase_navbar_extra = '';
        }
    ?>

<form id="FormularioRegistroLista" method="post">
    
    <input type="hidden" name="estructura_id" id="estructura_id" value="<?php echo $estructura_id; ?>" />
    <input type="hidden" name="codigo_rubro" id="codigo_rubro" value="<?php echo $codigo_rubro; ?>" />
    <input type="hidden" name="vista_actual" id="vista_actual" value="<?php if($tipo_registro == 'nuevo_solcredito') { echo 'view_datos_generales'; } else { echo $vista_actual; } ?>" />
    <input type="hidden" name="home_ant_sig" id="home_ant_sig" value="" />
    
    <input type="hidden" name="tipo_registro" id="tipo_registro" value="<?php echo $tipo_registro; ?>" />
    <input type="hidden" name="ejecutivo_id" id="ejecutivo_id" value="<?php echo $arrRespuesta[0]['ejecutivo_id']; ?>" />
    
    <nav class="navbar sticky-top <?php echo $clase_navbar_extra; ?>"> <?php echo $this->mfunciones_microcreditos->getContenidoNavApp($estructura_id, $vista_actual, "unidad_familiar"); ?> </nav>

    <div class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">
    
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <div class='col-sm-12' style="text-align: center;"><label class='label-campo panel-heading' for='sol_actividad_principal' style="padding: 0px;"> <i class="fa fa-briefcase" aria-hidden="true"></i> ACTIVIDAD PRINCIPAL </label></div>
        <div style="clear: both"></div>
        
        <?php
            if($tipo_registro != 'nuevo_solcredito')
            {
                $arrTipoRegistro = $this->mfunciones_microcreditos->ObtenerTipoRegistro();
                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrTipoRegistro);
                
                if(isset($arrTipoRegistro[0]) && count($arrTipoRegistro[0]) > 0)
                {
                    echo '
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">SELECCIONE EL RUBRO <span style="text-shadow: 0 0 black;" class="AyudaTooltip" data-balloon-length="medium" data-balloon="No confundir con los formularios por rubro. Esta es una lista independiente al estudio de crédito. Al concluir el registro tendrá la opción de convertir la Solicitud a Estudio de Crédito seleccionando uno de los Rubros registrados en el sistema." data-balloon-pos="down"> </span></div>
                        <div class="panel-body" style="text-align: center;">
                            ' . html_select('sol_codigo_rubro', $arrTipoRegistro, 'tipo_persona_id', 'tipo_persona_nombre', '', $arrRespuesta[0]['sol_codigo_rubro']) . '
                        </div>
                    </div>
                    
                    <div style="clear: both"></div>

                    <br />';
                }
                else
                {
                    echo $this->lang->line('TablaNoRegistros');
                }
            }
        ?>
        
        <div class="panel panel-default informacion_general datos_personales">
            <div class="panel-heading">DATOS PERSONALES</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_ci'><?php echo $this->lang->line('sol_ci'); ?>:</label><?php echo $arrCajasHTML['sol_ci']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_complemento'><?php echo $this->lang->line('sol_complemento'); ?>:</label><?php echo $arrCajasHTML['sol_complemento']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_extension'><?php echo $this->lang->line('sol_extension'); ?>:</label><?php echo $this->mfunciones_microcreditos->ObtenerCatalogoSelectSol('sol_extension', $arrRespuesta[0]['sol_extension'], 'cI_lugar_emisionoextension', '-1', 'aux_ci_creditos'); ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_primer_nombre'><?php echo $this->lang->line('sol_primer_nombre'); ?>:</label><?php echo $arrCajasHTML['sol_primer_nombre']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_segundo_nombre'><?php echo $this->lang->line('sol_segundo_nombre'); ?>:</label><?php echo $arrCajasHTML['sol_segundo_nombre']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_primer_apellido'><?php echo $this->lang->line('sol_primer_apellido'); ?>:</label><?php echo $arrCajasHTML['sol_primer_apellido']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_segundo_apellido'><?php echo $this->lang->line('sol_segundo_apellido'); ?>:</label><?php echo $arrCajasHTML['sol_segundo_apellido']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_correo'><?php echo $this->lang->line('sol_correo'); ?>:</label><?php echo $arrCajasHTML['sol_correo']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_cel'><?php echo $this->lang->line('sol_cel'); ?>:</label><?php echo $arrCajasHTML['sol_cel']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_telefono'><?php echo $this->lang->line('sol_telefono'); ?>:</label><?php echo $arrCajasHTML['sol_telefono']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_fec_nac'><?php echo $this->lang->line('sol_fec_nac'); ?>:</label><?php echo $arrCajasHTML['sol_fec_nac']; ?></div></div>
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_est_civil'><?php echo $this->lang->line('sol_est_civil'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_est_civil', $arrRespuesta[0]['sol_est_civil'], 'di_estadocivil'); ?></div></div>
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_nit'><?php echo $this->lang->line('sol_nit'); ?>:</label><?php echo $arrCajasHTML['sol_nit']; ?></div></div>
                <div class='col-sm-4-aux' style='text-align: center;'><div class='form-group'><label class='label-campo' for='sol_cliente'><?php echo $this->lang->line('sol_cliente'); ?>:</label><br /><?php echo $arrCajasHTML['sol_cliente']; ?></div></div>
                <div class='col-sm-4' style='text-align: center;'>
                    <div class='form-group'>
                        <label class='label-campo' for='sol_conyugue'><?php echo $this->lang->line('sol_conyugue'); ?>:</label> 
                        <span style="text-shadow: 0 0 black;" class="AyudaTooltip" data-balloon-length="medium" data-balloon="Al registrar la actividad del cónyuge se adicionarán más pantallas para que pueda registrar la información del cónyuge. IMPORTANTE: Si ya registró información del cónyuge y marca la opción 'No' la información registrada SERÁ BORRADA y sólo se mostrarán las pantallas del registro del Solicitante." data-balloon-pos="left"> </span>
                            <?php echo (str_replace(' ', '', $arrRespuesta[0]['sol_con_ci'])!='' ? "<br /><label class='label-campo' for='' style='padding: 0px; color: #ff0000;'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> ¡Registró información del cónyuge!</label>" : ""); ?>
                            
                            <br />
                                
                            <?php $sw_conyuge = (str_replace(' ', '', $arrRespuesta[0]['sol_con_ci'])!='' ? 1 : 0); ?>
                            
                            <div id="sol_conyugue_opcion" style="display: <?php echo ($sw_conyuge==0 ? 'none' : 'block'); ?>;">
                                <span class="EnlaceSimple label-campo" onclick="Mostrar_sol_conyugue_marcar();">
                                    <strong> <i class="fa fa-pencil" aria-hidden="true"></i> ¿Cambiar valor? </strong>
                                </span>
                            </div>
                            
                            <div id="sol_conyugue_marcar" style="display: <?php echo ($sw_conyuge==1 ? 'none' : 'block'); ?>;">
                                <?php echo $arrCajasHTML['sol_conyugue']; ?>
                            </div>
                    </div>
                </div>
                
            </div>
        </div>
              
        <div class='col-sm-12' style="text-align: center;"><div class='form-group'><label class='label-campo' for='sol_dependencia'><?php echo $this->lang->line('sol_dependencia'); ?>:</label><br /><?php echo $arrCajasHTML['sol_dependencia']; ?></div></div>
        <div style="clear: both"></div>
        
        <div class="panel panel-default informacion_general sol_act_ind">
            <div class="panel-heading">ACTIVIDAD INDEPENDIENTE</div>
            <div class="panel-body">
                
                
                <div class='col-sm-4'><div class='form-group'><label class='label-campo' for='sol_indepen_actividad'><?php echo $this->lang->line('sol_indepen_actividad'); ?>:</label><?php echo $arrCajasHTML['sol_indepen_actividad']; ?></div></div>
                
                <div class='col-sm-4'><div class='form-group'><label class='label-campo' for='sol_indepen_telefono'><?php echo $this->lang->line('sol_indepen_telefono'); ?>:</label><?php echo $arrCajasHTML['sol_indepen_telefono']; ?></div></div>

                <div class='col-sm-4'><div class='form-group'>
                
                    <div class="row">

                        <div class="col" style="text-align: center;">
                            <label style="text-shadow: none;" class='label-campo texto-centrado' for=''>Antigüedad en la actividad</label>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col" style="text-align: center;">
                            <label class='label-campo' for=''> <?php echo $this->lang->line('sol_indepen_ant_ano'); ?> </label>
                        </div>
                        <div class="col" style="text-align: left;">
                            <?php echo $arrCajasHTML['sol_indepen_ant_ano']; ?>
                        </div>
                        <div class="col" style="text-align: center;">
                            <label class='label-campo' for=''> <?php echo $this->lang->line('sol_indepen_ant_mes'); ?> </label>
                        </div>
                        <div class="col" style="text-align: left;">
                            <?php echo $arrCajasHTML['sol_indepen_ant_mes']; ?>
                        </div>

                    </div>
                    
                </div></div>
                
                <div class='col-sm-12' style="padding-bottom: 0px; text-align: center;"><label style="text-shadow: none;" class='label-campo texto-centrado' for=''> Horario y días de atención </label> </div>
                
                <div class='col-sm-4'><div class='form-group'>
                        
                    <div class="row">

                        <div class="col" style="text-align: left;">
                            <label class='label-campo' for=''> Atención <?php echo $this->lang->line('sol_indepen_horario_desde'); ?> </label>
                            <br />
                            <?php echo $arrCajasHTML['sol_indepen_horario_desde']; ?>
                        </div>
                        <div class="col" style="text-align: left;">
                            <label class='label-campo' for=''> <?php echo $this->lang->line('sol_indepen_horario_hasta'); ?> </label>
                            <br />
                            <?php echo $arrCajasHTML['sol_indepen_horario_hasta']; ?>
                        </div>

                    </div>
                    
                </div></div>
                
                <div class='col-sm-7'><div class='form-group'><label class='label-campo' for='sol_indepen_horario_dias'><?php echo $this->lang->line('sol_indepen_horario_dias'); ?>:</label>
                    
                <?php $arr_sol_indepen_horario_dias = explode(",", $arrRespuesta[0]['sol_indepen_horario_dias']); ?>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php $seleccion = ''; if (in_array("1", $arr_sol_indepen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d1_1" type="checkbox" name="sol_indepen_horario_dias_list[]" <?php echo $seleccion; ?> value="1">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d1_1"><span></span>Lunes</label>
                            <br />
                            <?php $seleccion = ''; if (in_array("2", $arr_sol_indepen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d1_2" type="checkbox" name="sol_indepen_horario_dias_list[]" <?php echo $seleccion; ?> value="2">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d1_2"><span></span>Martes</label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php $seleccion = ''; if (in_array("3", $arr_sol_indepen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d1_3" type="checkbox" name="sol_indepen_horario_dias_list[]" <?php echo $seleccion; ?> value="3">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d1_3"><span></span>Miércoles</label>
                            <br />
                            <?php $seleccion = ''; if (in_array("4", $arr_sol_indepen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d1_4" type="checkbox" name="sol_indepen_horario_dias_list[]" <?php echo $seleccion; ?> value="4">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d1_4"><span></span>Jueves</label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php $seleccion = ''; if (in_array("5", $arr_sol_indepen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d1_5" type="checkbox" name="sol_indepen_horario_dias_list[]" <?php echo $seleccion; ?> value="5">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d1_5"><span></span>Viernes</label>
                            <br />
                            <?php $seleccion = ''; if (in_array("6", $arr_sol_indepen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d1_6" type="checkbox" name="sol_indepen_horario_dias_list[]" <?php echo $seleccion; ?> value="6">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d1_6"><span></span>Sábado</label>
                            <br />
                            <?php $seleccion = ''; if (in_array("7", $arr_sol_indepen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d1_7" type="checkbox" name="sol_indepen_horario_dias_list[]" <?php echo $seleccion; ?> value="7">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d1_7"><span></span>Domingo</label>
                        </div>
                    </div>
                
                </div></div>
                
            </div>
        </div>
        
        <div class="panel panel-default informacion_general sol_act_dep">
            <div class="panel-heading">ACTIVIDAD DEPENDIENTE</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_depen_empresa'><?php echo $this->lang->line('sol_depen_empresa'); ?>:</label><?php echo $arrCajasHTML['sol_depen_empresa']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_depen_actividad'><?php echo $this->lang->line('sol_depen_actividad'); ?>:</label><?php echo $arrCajasHTML['sol_depen_actividad']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_depen_cargo'><?php echo $this->lang->line('sol_depen_cargo'); ?>:</label><?php echo $arrCajasHTML['sol_depen_cargo']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_depen_tipo_contrato'><?php echo $this->lang->line('sol_depen_tipo_contrato'); ?>:</label><?php echo $arrCajasHTML['sol_depen_tipo_contrato']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_depen_telefono'><?php echo $this->lang->line('sol_depen_telefono'); ?>:</label><?php echo $arrCajasHTML['sol_depen_telefono']; ?></div></div>
                
                <div class='col-sm-4'><div class='form-group'>
                
                    <div class="row">

                        <div class="col" style="text-align: center;">
                            <label style="text-shadow: none;" class='label-campo texto-centrado' for=''>Antigüedad en la actividad</label>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col" style="text-align: center;">
                            <label class='label-campo' for=''> <?php echo $this->lang->line('sol_depen_ant_ano'); ?> </label>
                        </div>
                        <div class="col" style="text-align: left;">
                            <?php echo $arrCajasHTML['sol_depen_ant_ano']; ?>
                        </div>
                        <div class="col" style="text-align: center;">
                            <label class='label-campo' for=''> <?php echo $this->lang->line('sol_depen_ant_mes'); ?> </label>
                        </div>
                        <div class="col" style="text-align: left;">
                            <?php echo $arrCajasHTML['sol_depen_ant_mes']; ?>
                        </div>

                    </div>
                    
                </div></div>
                
                <div class='col-sm-12' style="padding-bottom: 0px; text-align: center;"><label style="text-shadow: none;" class='label-campo texto-centrado' for=''> Horario y días de trabajo </label> </div>
                
                <div class='col-sm-4'><div class='form-group'>
                        
                    <div class="row">

                        <div class="col" style="text-align: left;">
                            <label class='label-campo' for=''> Atención <?php echo $this->lang->line('sol_depen_horario_desde'); ?> </label>
                            <br />
                            <?php echo $arrCajasHTML['sol_depen_horario_desde']; ?>
                        </div>
                        <div class="col" style="text-align: left;">
                            <label class='label-campo' for=''> <?php echo $this->lang->line('sol_depen_horario_hasta'); ?> </label>
                            <br />
                            <?php echo $arrCajasHTML['sol_depen_horario_hasta']; ?>
                        </div>

                    </div>
                    
                </div></div>
                
                <div class='col-sm-7'><div class='form-group'><label class='label-campo' for='sol_depen_horario_dias'><?php echo $this->lang->line('sol_depen_horario_dias'); ?>:</label>
                    
                <?php $arr_sol_depen_horario_dias = explode(",", $arrRespuesta[0]['sol_depen_horario_dias']); ?>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php $seleccion = ''; if (in_array("1", $arr_sol_depen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d2_1" type="checkbox" name="sol_depen_horario_dias_list[]" <?php echo $seleccion; ?> value="1">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d2_1"><span></span>Lunes</label>
                            <br />
                            <?php $seleccion = ''; if (in_array("2", $arr_sol_depen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d2_2" type="checkbox" name="sol_depen_horario_dias_list[]" <?php echo $seleccion; ?> value="2">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d2_2"><span></span>Martes</label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php $seleccion = ''; if (in_array("3", $arr_sol_depen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d2_3" type="checkbox" name="sol_depen_horario_dias_list[]" <?php echo $seleccion; ?> value="3">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d2_3"><span></span>Miércoles</label>
                            <br />
                            <?php $seleccion = ''; if (in_array("4", $arr_sol_depen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d2_4" type="checkbox" name="sol_depen_horario_dias_list[]" <?php echo $seleccion; ?> value="4">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d2_4"><span></span>Jueves</label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php $seleccion = ''; if (in_array("5", $arr_sol_depen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d2_5" type="checkbox" name="sol_depen_horario_dias_list[]" <?php echo $seleccion; ?> value="5">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d2_5"><span></span>Viernes</label>
                            <br />
                            <?php $seleccion = ''; if (in_array("6", $arr_sol_depen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d2_6" type="checkbox" name="sol_depen_horario_dias_list[]" <?php echo $seleccion; ?> value="6">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d2_6"><span></span>Sábado</label>
                            <br />
                            <?php $seleccion = ''; if (in_array("7", $arr_sol_depen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d2_7" type="checkbox" name="sol_depen_horario_dias_list[]" <?php echo $seleccion; ?> value="7">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d2_7"><span></span>Domingo</label>
                        </div>
                    </div>
                
                </div></div>
                
            </div>
        </div>
        
        <!-- Actividad Secundaria -->
        
        <div class='col-sm-12' style="text-align: center;">
            <div class='form-group'>
                <label class='label-campo panel-heading' for='sol_actividad_secundaria' style="padding: 10px 0px 0px 0px;"> <span style="font-size: 0.95em;"><i class="fa fa-briefcase" aria-hidden="true"></i> <?php echo ($this->lang->line('sol_actividad_secundaria')); ?></span></label>
                <span style="text-shadow: 0 0 black;" class="AyudaTooltip" data-balloon-length="medium" data-balloon="El registro de la Actividad Secundaria es opcional, sin embargo, si marca la opción 'Si' deberá seleccionar un rubro y registrar correctamente la actividad económica. IMPORTANTE: Si ya registró información de la Actividad Secundaria y marca la opción 'No' la información registrada SERÁ BORRADA." data-balloon-pos="left"> </span>
                
                
                
                <?php
                
                $sw_secundaria = ((int)$arrRespuesta[0]['sol_dependencia_sec']!=0);
                
                    echo ($sw_secundaria==1 ? "<br /><label class='label-campo' for='' style='padding: 0px; color: #ff0000;'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> ¡Registró información!</label>" : ""); ?>
                            
                <br />

                <div id="sol_secundaria_opcion" style="display: <?php echo ($sw_secundaria==0 ? 'none' : 'block'); ?>;">
                    <span class="EnlaceSimple label-campo" onclick="Mostrar_sol_secundaria_marcar();">
                        <strong> <i class="fa fa-pencil" aria-hidden="true"></i> ¿Cambiar valor? </strong>
                    </span>
                </div>

                <div id="sol_secundaria_marcar" style="display: <?php echo ($sw_secundaria==1 ? 'none' : 'block'); ?>;">
                    <?php echo $arrCajasHTML['sol_actividad_secundaria']; ?>
                </div>
                
            </div>
        </div>
        <div style="clear: both"></div>
        
        <div id="bloque_secundaria">
        
            <?php
            $arrTipoRegistro = $this->mfunciones_microcreditos->ObtenerTipoRegistro(' AND tipo_persona_id IN (1,2,3,4)');
            $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrTipoRegistro);

            if(isset($arrTipoRegistro[0]) && count($arrTipoRegistro[0]) > 0)
            {
                echo '

                <div class="panel panel-default">
                    <div class="panel-heading">SELECCIONE EL RUBRO <span style="text-shadow: 0 0 black;" class="AyudaTooltip" data-balloon-length="medium" data-balloon="Para la Actividad Secundaria se listan únicamente los rubros de estudio de crédito." data-balloon-pos="down"> </span></div>
                    <div class="panel-body" style="text-align: center;">
                        ' . html_select('sol_codigo_rubro_sec', $arrTipoRegistro, 'tipo_persona_id', 'tipo_persona_nombre', '', $arrRespuesta[0]['sol_codigo_rubro_sec']) . '
                    </div>
                </div>

                <div style="clear: both"></div>';
            }
            else
            {
                echo $this->lang->line('TablaNoRegistros');
            }
            ?>
            
            <div class='col-sm-12' style="text-align: center;"><div class='form-group'><label class='label-campo' for='sol_dependencia_sec'><?php echo $this->lang->line('sol_dependencia_sec'); ?>:</label><br /><?php echo $arrCajasHTML['sol_dependencia_sec']; ?></div></div>
            <div style="clear: both"></div>

            <div class="panel panel-default informacion_general sol_act_ind_sec">
                <div class="panel-heading">ACTIVIDAD INDEPENDIENTE</div>
                <div class="panel-body">


                    <div class='col-sm-4'><div class='form-group'><label class='label-campo' for='sol_indepen_actividad_sec'><?php echo $this->lang->line('sol_indepen_actividad_sec'); ?>:</label><?php echo $arrCajasHTML['sol_indepen_actividad_sec']; ?></div></div>

                    <div class='col-sm-4'><div class='form-group'><label class='label-campo' for='sol_indepen_telefono_sec'><?php echo $this->lang->line('sol_indepen_telefono_sec'); ?>:</label><?php echo $arrCajasHTML['sol_indepen_telefono_sec']; ?></div></div>

                    <div class='col-sm-4'><div class='form-group'>

                        <div class="row">

                            <div class="col" style="text-align: center;">
                                <label style="text-shadow: none;" class='label-campo texto-centrado' for=''>Antigüedad en la actividad</label>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col" style="text-align: center;">
                                <label class='label-campo' for=''> <?php echo $this->lang->line('sol_indepen_ant_ano_sec'); ?> </label>
                            </div>
                            <div class="col" style="text-align: left;">
                                <?php echo $arrCajasHTML['sol_indepen_ant_ano_sec']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='label-campo' for=''> <?php echo $this->lang->line('sol_indepen_ant_mes_sec'); ?> </label>
                            </div>
                            <div class="col" style="text-align: left;">
                                <?php echo $arrCajasHTML['sol_indepen_ant_mes_sec']; ?>
                            </div>

                        </div>

                    </div></div>

                    <div class='col-sm-12' style="padding-bottom: 0px; text-align: center;"><label style="text-shadow: none;" class='label-campo texto-centrado' for=''> Horario y días de atención </label> </div>

                    <div class='col-sm-4'><div class='form-group'>

                        <div class="row">

                            <div class="col" style="text-align: left;">
                                <label class='label-campo' for=''> Atención <?php echo $this->lang->line('sol_indepen_horario_desde_sec'); ?> </label>
                                <br />
                                <?php echo $arrCajasHTML['sol_indepen_horario_desde_sec']; ?>
                            </div>
                            <div class="col" style="text-align: left;">
                                <label class='label-campo' for=''> <?php echo $this->lang->line('sol_indepen_horario_hasta_sec'); ?> </label>
                                <br />
                                <?php echo $arrCajasHTML['sol_indepen_horario_hasta_sec']; ?>
                            </div>

                        </div>

                    </div></div>

                    <div class='col-sm-7'><div class='form-group'><label class='label-campo' for='sol_indepen_horario_dias_sec'><?php echo $this->lang->line('sol_indepen_horario_dias_sec'); ?>:</label>

                    <?php $arr_sol_indepen_horario_dias_sec = explode(",", $arrRespuesta[0]['sol_indepen_horario_dias_sec']); ?>
                        <div class="row">
                            <div class="col" style="text-align: justify;">
                                <?php $seleccion = ''; if (in_array("1", $arr_sol_indepen_horario_dias_sec)){ $seleccion = 'checked="checked"'; } ?>
                                <input id="d3_1" type="checkbox" name="sol_indepen_horario_dias_list_sec[]" <?php echo $seleccion; ?> value="1">
                                <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d3_1"><span></span>Lunes</label>
                                <br />
                                <?php $seleccion = ''; if (in_array("2", $arr_sol_indepen_horario_dias_sec)){ $seleccion = 'checked="checked"'; } ?>
                                <input id="d3_2" type="checkbox" name="sol_indepen_horario_dias_list_sec[]" <?php echo $seleccion; ?> value="2">
                                <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d3_2"><span></span>Martes</label>
                            </div>
                            <div class="col" style="text-align: justify;">
                                <?php $seleccion = ''; if (in_array("3", $arr_sol_indepen_horario_dias_sec)){ $seleccion = 'checked="checked"'; } ?>
                                <input id="d3_3" type="checkbox" name="sol_indepen_horario_dias_list_sec[]" <?php echo $seleccion; ?> value="3">
                                <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d3_3"><span></span>Miércoles</label>
                                <br />
                                <?php $seleccion = ''; if (in_array("4", $arr_sol_indepen_horario_dias_sec)){ $seleccion = 'checked="checked"'; } ?>
                                <input id="d3_4" type="checkbox" name="sol_indepen_horario_dias_list_sec[]" <?php echo $seleccion; ?> value="4">
                                <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d3_4"><span></span>Jueves</label>
                            </div>
                            <div class="col" style="text-align: justify;">
                                <?php $seleccion = ''; if (in_array("5", $arr_sol_indepen_horario_dias_sec)){ $seleccion = 'checked="checked"'; } ?>
                                <input id="d3_5" type="checkbox" name="sol_indepen_horario_dias_list_sec[]" <?php echo $seleccion; ?> value="5">
                                <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d3_5"><span></span>Viernes</label>
                                <br />
                                <?php $seleccion = ''; if (in_array("6", $arr_sol_indepen_horario_dias_sec)){ $seleccion = 'checked="checked"'; } ?>
                                <input id="d3_6" type="checkbox" name="sol_indepen_horario_dias_list_sec[]" <?php echo $seleccion; ?> value="6">
                                <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d3_6"><span></span>Sábado</label>
                                <br />
                                <?php $seleccion = ''; if (in_array("7", $arr_sol_indepen_horario_dias_sec)){ $seleccion = 'checked="checked"'; } ?>
                                <input id="d3_7" type="checkbox" name="sol_indepen_horario_dias_list_sec[]" <?php echo $seleccion; ?> value="7">
                                <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d3_7"><span></span>Domingo</label>
                            </div>
                        </div>

                    </div></div>

                </div>
            </div>

            <div class="panel panel-default informacion_general sol_act_dep_sec">
                <div class="panel-heading">ACTIVIDAD DEPENDIENTE</div>
                <div class="panel-body">

                    <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_depen_empresa_sec'><?php echo $this->lang->line('sol_depen_empresa_sec'); ?>:</label><?php echo $arrCajasHTML['sol_depen_empresa_sec']; ?></div></div>
                    <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_depen_actividad_sec'><?php echo $this->lang->line('sol_depen_actividad_sec'); ?>:</label><?php echo $arrCajasHTML['sol_depen_actividad_sec']; ?></div></div>
                    <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_depen_cargo_sec'><?php echo $this->lang->line('sol_depen_cargo_sec'); ?>:</label><?php echo $arrCajasHTML['sol_depen_cargo_sec']; ?></div></div>
                    <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_depen_tipo_contrato_sec'><?php echo $this->lang->line('sol_depen_tipo_contrato_sec'); ?>:</label><?php echo $arrCajasHTML['sol_depen_tipo_contrato_sec']; ?></div></div>
                    <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_depen_telefono_sec'><?php echo $this->lang->line('sol_depen_telefono_sec'); ?>:</label><?php echo $arrCajasHTML['sol_depen_telefono_sec']; ?></div></div>

                    <div class='col-sm-4'><div class='form-group'>

                        <div class="row">

                            <div class="col" style="text-align: center;">
                                <label style="text-shadow: none;" class='label-campo texto-centrado' for=''>Antigüedad en la actividad</label>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col" style="text-align: center;">
                                <label class='label-campo' for=''> <?php echo $this->lang->line('sol_depen_ant_ano_sec'); ?> </label>
                            </div>
                            <div class="col" style="text-align: left;">
                                <?php echo $arrCajasHTML['sol_depen_ant_ano_sec']; ?>
                            </div>
                            <div class="col" style="text-align: center;">
                                <label class='label-campo' for=''> <?php echo $this->lang->line('sol_depen_ant_mes_sec'); ?> </label>
                            </div>
                            <div class="col" style="text-align: left;">
                                <?php echo $arrCajasHTML['sol_depen_ant_mes_sec']; ?>
                            </div>

                        </div>

                    </div></div>

                    <div class='col-sm-12' style="padding-bottom: 0px; text-align: center;"><label style="text-shadow: none;" class='label-campo texto-centrado' for=''> Horario y días de trabajo </label> </div>

                    <div class='col-sm-4'><div class='form-group'>

                        <div class="row">

                            <div class="col" style="text-align: left;">
                                <label class='label-campo' for=''> Atención <?php echo $this->lang->line('sol_depen_horario_desde_sec'); ?> </label>
                                <br />
                                <?php echo $arrCajasHTML['sol_depen_horario_desde_sec']; ?>
                            </div>
                            <div class="col" style="text-align: left;">
                                <label class='label-campo' for=''> <?php echo $this->lang->line('sol_depen_horario_hasta_sec'); ?> </label>
                                <br />
                                <?php echo $arrCajasHTML['sol_depen_horario_hasta_sec']; ?>
                            </div>

                        </div>

                    </div></div>

                    <div class='col-sm-7'><div class='form-group'><label class='label-campo' for='sol_depen_horario_dias_sec'><?php echo $this->lang->line('sol_depen_horario_dias_sec'); ?>:</label>

                    <?php $arr_sol_depen_horario_dias_sec = explode(",", $arrRespuesta[0]['sol_depen_horario_dias_sec']); ?>
                        <div class="row">
                            <div class="col" style="text-align: justify;">
                                <?php $seleccion = ''; if (in_array("1", $arr_sol_depen_horario_dias_sec)){ $seleccion = 'checked="checked"'; } ?>
                                <input id="d4_1" type="checkbox" name="sol_depen_horario_dias_list_sec[]" <?php echo $seleccion; ?> value="1">
                                <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d4_1"><span></span>Lunes</label>
                                <br />
                                <?php $seleccion = ''; if (in_array("2", $arr_sol_depen_horario_dias_sec)){ $seleccion = 'checked="checked"'; } ?>
                                <input id="d4_2" type="checkbox" name="sol_depen_horario_dias_list_sec[]" <?php echo $seleccion; ?> value="2">
                                <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d4_2"><span></span>Martes</label>
                            </div>
                            <div class="col" style="text-align: justify;">
                                <?php $seleccion = ''; if (in_array("3", $arr_sol_depen_horario_dias_sec)){ $seleccion = 'checked="checked"'; } ?>
                                <input id="d4_3" type="checkbox" name="sol_depen_horario_dias_list_sec[]" <?php echo $seleccion; ?> value="3">
                                <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d4_3"><span></span>Miércoles</label>
                                <br />
                                <?php $seleccion = ''; if (in_array("4", $arr_sol_depen_horario_dias_sec)){ $seleccion = 'checked="checked"'; } ?>
                                <input id="d4_4" type="checkbox" name="sol_depen_horario_dias_list_sec[]" <?php echo $seleccion; ?> value="4">
                                <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d4_4"><span></span>Jueves</label>
                            </div>
                            <div class="col" style="text-align: justify;">
                                <?php $seleccion = ''; if (in_array("5", $arr_sol_depen_horario_dias_sec)){ $seleccion = 'checked="checked"'; } ?>
                                <input id="d4_5" type="checkbox" name="sol_depen_horario_dias_list_sec[]" <?php echo $seleccion; ?> value="5">
                                <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d4_5"><span></span>Viernes</label>
                                <br />
                                <?php $seleccion = ''; if (in_array("6", $arr_sol_depen_horario_dias_sec)){ $seleccion = 'checked="checked"'; } ?>
                                <input id="d4_6" type="checkbox" name="sol_depen_horario_dias_list_sec[]" <?php echo $seleccion; ?> value="6">
                                <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d4_6"><span></span>Sábado</label>
                                <br />
                                <?php $seleccion = ''; if (in_array("7", $arr_sol_depen_horario_dias_sec)){ $seleccion = 'checked="checked"'; } ?>
                                <input id="d4_7" type="checkbox" name="sol_depen_horario_dias_list_sec[]" <?php echo $seleccion; ?> value="7">
                                <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d4_7"><span></span>Domingo</label>
                            </div>
                        </div>

                    </div></div>

                </div>
            </div>
            
        </div>
        
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>