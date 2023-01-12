<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios('sincombo');
?>

    $(document).ready(function() {
        
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
            $("#sol_con_cliente, #sol_con_dependencia").togglebutton();
            $("div.modal-backdrop").remove();
        });
        
        $('.datos_personales').find("input[type=text]").each(function(ev)
        {
            $(this).attr("style","text-transform: capitalize;");
        });
    });

    sol_con_actvidad();

    // ### ACTIVIDAD ECONÓMICA  INICIO
    
    // Cónyuge
    function sol_con_actvidad()
    {
        $(".sol_con_act_ind, .sol_con_act_dep").hide();
            
        switch (parseInt($("#sol_con_dependencia").val())) {
            case 1:
                $(".sol_con_act_dep").fadeIn();
                break;
            case 2:
                $(".sol_con_act_ind").fadeIn();
                break;
            default:
                break;
        }
    }

    $("#sol_con_dependencia").on('change', function(){
        sol_con_actvidad();
    });

    // ### ACTIVIDAD ECONÓMICA  FIN

    // Si no fue seleccionado Cónyuge, se muestra el contenido vacío.
    if(<?php echo (int)$arrRespuesta[0]['sol_conyugue']; ?>==0)
    {
        $("#estructura_principal").html('<?php echo $this->lang->line('sol_con_sin_seleccion'); ?>');
        
        $("#home_ant_sig").val("sol_credito_solicitado");
        EnviarSinGuardar();
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

    <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
    
    <div id='estructura_principal' class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <div class="panel panel-default informacion_general datos_personales">
            <div class="panel-heading">CÓNYUGE - DATOS PERSONALES</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_ci'><?php echo $this->lang->line('sol_con_ci'); ?>:</label><?php echo $arrCajasHTML['sol_con_ci']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_complemento'><?php echo $this->lang->line('sol_con_complemento'); ?>:</label><?php echo $arrCajasHTML['sol_con_complemento']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_extension'><?php echo $this->lang->line('sol_con_extension'); ?>:</label><?php echo $this->mfunciones_microcreditos->ObtenerCatalogoSelectSol('sol_con_extension', $arrRespuesta[0]['sol_con_extension'], 'cI_lugar_emisionoextension', '-1', 'aux_ci_creditos'); ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_primer_nombre'><?php echo $this->lang->line('sol_con_primer_nombre'); ?>:</label><?php echo $arrCajasHTML['sol_con_primer_nombre']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_segundo_nombre'><?php echo $this->lang->line('sol_con_segundo_nombre'); ?>:</label><?php echo $arrCajasHTML['sol_con_segundo_nombre']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_primer_apellido'><?php echo $this->lang->line('sol_con_primer_apellido'); ?>:</label><?php echo $arrCajasHTML['sol_con_primer_apellido']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_segundo_apellido'><?php echo $this->lang->line('sol_con_segundo_apellido'); ?>:</label><?php echo $arrCajasHTML['sol_con_segundo_apellido']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_correo'><?php echo $this->lang->line('sol_con_correo'); ?>:</label><?php echo $arrCajasHTML['sol_con_correo']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_cel'><?php echo $this->lang->line('sol_con_cel'); ?>:</label><?php echo $arrCajasHTML['sol_con_cel']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_telefono'><?php echo $this->lang->line('sol_con_telefono'); ?>:</label><?php echo $arrCajasHTML['sol_con_telefono']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_fec_nac'><?php echo $this->lang->line('sol_con_fec_nac'); ?>:</label><?php echo $arrCajasHTML['sol_con_fec_nac']; ?></div></div>
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_est_civil'><?php echo $this->lang->line('sol_con_est_civil'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_con_est_civil', $arrRespuesta[0]['sol_con_est_civil'], 'di_estadocivil'); ?></div></div>
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_nit'><?php echo $this->lang->line('sol_con_nit'); ?>:</label><?php echo $arrCajasHTML['sol_con_nit']; ?></div></div>
                <div class='col-sm-4-aux' style='text-align: center;'><div class='form-group'><label class='label-campo' for='sol_con_cliente'><?php echo $this->lang->line('sol_con_cliente'); ?>:</label><br /><?php echo $arrCajasHTML['sol_con_cliente']; ?></div></div>
                
            </div>
        </div>
        
        <div class='col-sm-12' style="text-align: center;"><div class='form-group'><label class='label-campo' for='sol_con_dependencia'>Cónyuge - <?php echo $this->lang->line('sol_con_dependencia'); ?>:</label><br /><?php echo $arrCajasHTML['sol_con_dependencia']; ?></div></div>
        <div style="clear: both"></div>
        
        <div class="panel panel-default informacion_general sol_con_act_ind">
            <div class="panel-heading">CÓNYUGE - ACTIVIDAD INDEPENDIENTE</div>
            <div class="panel-body">
                
                
                <div class='col-sm-4'><div class='form-group'><label class='label-campo' for='sol_con_indepen_actividad'><?php echo $this->lang->line('sol_con_indepen_actividad'); ?>:</label><?php echo $arrCajasHTML['sol_con_indepen_actividad']; ?></div></div>
                
                <div class='col-sm-4'><div class='form-group'><label class='label-campo' for='sol_con_indepen_telefono'><?php echo $this->lang->line('sol_con_indepen_telefono'); ?>:</label><?php echo $arrCajasHTML['sol_con_indepen_telefono']; ?></div></div>

                <div class='col-sm-4'><div class='form-group'>
                
                    <div class="row">

                        <div class="col" style="text-align: center;">
                            <label style="text-shadow: none;" class='label-campo texto-centrado' for=''>Antigüedad en la actividad</label>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col" style="text-align: center;">
                            <label class='label-campo' for=''> <?php echo $this->lang->line('sol_con_indepen_ant_ano'); ?> </label>
                        </div>
                        <div class="col" style="text-align: left;">
                            <?php echo $arrCajasHTML['sol_con_indepen_ant_ano']; ?>
                        </div>
                        <div class="col" style="text-align: center;">
                            <label class='label-campo' for=''> <?php echo $this->lang->line('sol_con_indepen_ant_mes'); ?> </label>
                        </div>
                        <div class="col" style="text-align: left;">
                            <?php echo $arrCajasHTML['sol_con_indepen_ant_mes']; ?>
                        </div>

                    </div>
                    
                </div></div>
                
                <div class='col-sm-12' style="padding-bottom: 0px; text-align: center;"><label style="text-shadow: none;" class='label-campo texto-centrado' for=''> Horario y días de atención </label> </div>
                
                <div class='col-sm-4'><div class='form-group'>
                        
                    <div class="row">

                        <div class="col" style="text-align: left;">
                            <label class='label-campo' for=''> Atención <?php echo $this->lang->line('sol_con_indepen_horario_desde'); ?> </label>
                            <br />
                            <?php echo $arrCajasHTML['sol_con_indepen_horario_desde']; ?>
                        </div>
                        <div class="col" style="text-align: left;">
                            <label class='label-campo' for=''> <?php echo $this->lang->line('sol_con_indepen_horario_hasta'); ?> </label>
                            <br />
                            <?php echo $arrCajasHTML['sol_con_indepen_horario_hasta']; ?>
                        </div>

                    </div>
                    
                </div></div>
                
                <div class='col-sm-7'><div class='form-group'><label class='label-campo' for='sol_con_indepen_horario_dias'><?php echo $this->lang->line('sol_con_indepen_horario_dias'); ?>:</label>
                    
                <?php $arr_sol_con_indepen_horario_dias = explode(",", $arrRespuesta[0]['sol_con_indepen_horario_dias']); ?>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php $seleccion = ''; if (in_array("1", $arr_sol_con_indepen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d3_1" type="checkbox" name="sol_con_indepen_horario_dias_list[]" <?php echo $seleccion; ?> value="1">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d3_1"><span></span>Lunes</label>
                            <br />
                            <?php $seleccion = ''; if (in_array("2", $arr_sol_con_indepen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d3_2" type="checkbox" name="sol_con_indepen_horario_dias_list[]" <?php echo $seleccion; ?> value="2">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d3_2"><span></span>Martes</label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php $seleccion = ''; if (in_array("3", $arr_sol_con_indepen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d3_3" type="checkbox" name="sol_con_indepen_horario_dias_list[]" <?php echo $seleccion; ?> value="3">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d3_3"><span></span>Miércoles</label>
                            <br />
                            <?php $seleccion = ''; if (in_array("4", $arr_sol_con_indepen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d3_4" type="checkbox" name="sol_con_indepen_horario_dias_list[]" <?php echo $seleccion; ?> value="4">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d3_4"><span></span>Jueves</label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php $seleccion = ''; if (in_array("5", $arr_sol_con_indepen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d3_5" type="checkbox" name="sol_con_indepen_horario_dias_list[]" <?php echo $seleccion; ?> value="5">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d3_5"><span></span>Viernes</label>
                            <br />
                            <?php $seleccion = ''; if (in_array("6", $arr_sol_con_indepen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d3_6" type="checkbox" name="sol_con_indepen_horario_dias_list[]" <?php echo $seleccion; ?> value="6">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d3_6"><span></span>Sábado</label>
                            <br />
                            <?php $seleccion = ''; if (in_array("7", $arr_sol_con_indepen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d3_7" type="checkbox" name="sol_con_indepen_horario_dias_list[]" <?php echo $seleccion; ?> value="7">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d3_7"><span></span>Domingo</label>
                        </div>
                    </div>
                
                </div></div>
                
            </div>
        </div>
        
        <div class="panel panel-default informacion_general sol_con_act_dep">
            <div class="panel-heading">CÓNYUGE - ACTIVIDAD DEPENDIENTE</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_depen_empresa'><?php echo $this->lang->line('sol_con_depen_empresa'); ?>:</label><?php echo $arrCajasHTML['sol_con_depen_empresa']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_depen_actividad'><?php echo $this->lang->line('sol_con_depen_actividad'); ?>:</label><?php echo $arrCajasHTML['sol_con_depen_actividad']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_depen_cargo'><?php echo $this->lang->line('sol_con_depen_cargo'); ?>:</label><?php echo $arrCajasHTML['sol_con_depen_cargo']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_depen_tipo_contrato'><?php echo $this->lang->line('sol_con_depen_tipo_contrato'); ?>:</label><?php echo $arrCajasHTML['sol_con_depen_tipo_contrato']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_depen_telefono'><?php echo $this->lang->line('sol_con_depen_telefono'); ?>:</label><?php echo $arrCajasHTML['sol_con_depen_telefono']; ?></div></div>
                
                <div class='col-sm-4'><div class='form-group'>
                
                    <div class="row">

                        <div class="col" style="text-align: center;">
                            <label style="text-shadow: none;" class='label-campo texto-centrado' for=''>Antigüedad en la actividad</label>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col" style="text-align: center;">
                            <label class='label-campo' for=''> <?php echo $this->lang->line('sol_con_depen_ant_ano'); ?> </label>
                        </div>
                        <div class="col" style="text-align: left;">
                            <?php echo $arrCajasHTML['sol_con_depen_ant_ano']; ?>
                        </div>
                        <div class="col" style="text-align: center;">
                            <label class='label-campo' for=''> <?php echo $this->lang->line('sol_con_depen_ant_mes'); ?> </label>
                        </div>
                        <div class="col" style="text-align: left;">
                            <?php echo $arrCajasHTML['sol_con_depen_ant_mes']; ?>
                        </div>

                    </div>
                    
                </div></div>
                
                <div class='col-sm-12' style="padding-bottom: 0px; text-align: center;"><label style="text-shadow: none;" class='label-campo texto-centrado' for=''> Horario y días de trabajo </label> </div>
                
                <div class='col-sm-4'><div class='form-group'>
                        
                    <div class="row">

                        <div class="col" style="text-align: left;">
                            <label class='label-campo' for=''> Atención <?php echo $this->lang->line('sol_con_depen_horario_desde'); ?> </label>
                            <br />
                            <?php echo $arrCajasHTML['sol_con_depen_horario_desde']; ?>
                        </div>
                        <div class="col" style="text-align: left;">
                            <label class='label-campo' for=''> <?php echo $this->lang->line('sol_con_depen_horario_hasta'); ?> </label>
                            <br />
                            <?php echo $arrCajasHTML['sol_con_depen_horario_hasta']; ?>
                        </div>

                    </div>
                    
                </div></div>
                
                <div class='col-sm-7'><div class='form-group'><label class='label-campo' for='sol_con_depen_horario_dias'><?php echo $this->lang->line('sol_con_depen_horario_dias'); ?>:</label>
                    
                <?php $arr_sol_con_depen_horario_dias = explode(",", $arrRespuesta[0]['sol_con_depen_horario_dias']); ?>
                    <div class="row">
                        <div class="col" style="text-align: justify;">
                            <?php $seleccion = ''; if (in_array("1", $arr_sol_con_depen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d4_1" type="checkbox" name="sol_con_depen_horario_dias_list[]" <?php echo $seleccion; ?> value="1">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d4_1"><span></span>Lunes</label>
                            <br />
                            <?php $seleccion = ''; if (in_array("2", $arr_sol_con_depen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d4_2" type="checkbox" name="sol_con_depen_horario_dias_list[]" <?php echo $seleccion; ?> value="2">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d4_2"><span></span>Martes</label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php $seleccion = ''; if (in_array("3", $arr_sol_con_depen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d4_3" type="checkbox" name="sol_con_depen_horario_dias_list[]" <?php echo $seleccion; ?> value="3">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d4_3"><span></span>Miércoles</label>
                            <br />
                            <?php $seleccion = ''; if (in_array("4", $arr_sol_con_depen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d4_4" type="checkbox" name="sol_con_depen_horario_dias_list[]" <?php echo $seleccion; ?> value="4">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d4_4"><span></span>Jueves</label>
                        </div>
                        <div class="col" style="text-align: justify;">
                            <?php $seleccion = ''; if (in_array("5", $arr_sol_con_depen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d4_5" type="checkbox" name="sol_con_depen_horario_dias_list[]" <?php echo $seleccion; ?> value="5">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d4_5"><span></span>Viernes</label>
                            <br />
                            <?php $seleccion = ''; if (in_array("6", $arr_sol_con_depen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d4_6" type="checkbox" name="sol_con_depen_horario_dias_list[]" <?php echo $seleccion; ?> value="6">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d4_6"><span></span>Sábado</label>
                            <br />
                            <?php $seleccion = ''; if (in_array("7", $arr_sol_con_depen_horario_dias)){ $seleccion = 'checked="checked"'; } ?>
                            <input id="d4_7" type="checkbox" name="sol_con_depen_horario_dias_list[]" <?php echo $seleccion; ?> value="7">
                            <label style="margin-left: 0px !important; margin-bottom: 0px !important;" for="d4_7"><span></span>Domingo</label>
                        </div>
                    </div>
                
                </div></div>
                
            </div>
        </div>
        
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>