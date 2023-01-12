<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios('sincombo');
    
    if($tipo_registro == 'nuevo_norm')
    {
        echo '$(".informacion_operacion").hide();';
    }
    
?>

    $(document).ready(function() {
        $("#norm_rel_cred").togglebutton();
        $("div.modal-backdrop").remove();
        
        $('.contenido_formulario').find("input[type=text]").each(function(ev)
        {
            $(this).attr("style","text-transform: capitalize;");
        });
        
        $('.contenido_formulario').find("input[type=text]").each(function(ev)
        {
            $(this).attr("placeholder", " :: Registrar ::");
        });
        $('.contenido_formulario').find("input[type=tel]").each(function(ev)
        {
            $(this).attr("placeholder", " :: Registrar ::");
        });
        
        check_registro_num_proceso();
        norm_rel_cred();
    });
    
    $('#registro_num_proceso').on('keyup change', function(){
        check_registro_num_proceso();
    });

    function check_registro_num_proceso()
    {
        var valor = parseInt($("#registro_num_proceso").val() || 0);
        
        valor = valor.toString();
        
        if(!( /[^0-9]/.test( valor ) || valor.length != <?php echo (int)$this->lang->line('registro_num_proceso_cantidad'); ?>))
        {
            $('#registro_num_proceso_button').prop('disabled', false);
            
            $('#registro_num_proceso_label_error').hide();
            $('#registro_num_proceso_label_ok').show();
        }
        else
        {
            $('#registro_num_proceso_button').prop('disabled', true);
            
            $('#registro_num_proceso_label_ok').hide();
            $('#registro_num_proceso_label_error').show();
        }
    }
    
    function norm_rel_cred()
    {
        $("#norm_rel_cred_otro").fadeOut();

        if(parseInt($("#norm_rel_cred").val()) == 99)
        {
            $("#norm_rel_cred_otro").fadeIn();
        }
    }

    $("#norm_rel_cred").on('change', function(){
        norm_rel_cred();
    });
    
</script>

    <?php
    
        $text_unidad_familiar = '';
        if($tipo_registro == 'nuevo_norm')
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
    <input type="hidden" name="vista_actual" id="vista_actual" value="<?php if($tipo_registro == 'nuevo_norm') { echo 'view_norm_datos_generales'; } else { echo $vista_actual; } ?>" />
    <input type="hidden" name="home_ant_sig" id="home_ant_sig" value="" />
    
    <input type="hidden" name="tipo_registro" id="tipo_registro" value="<?php echo $tipo_registro; ?>" />
    <input type="hidden" name="ejecutivo_id" id="ejecutivo_id" value="<?php echo $arrRespuesta[0]['ejecutivo_id']; ?>" />
    
    <nav class="navbar sticky-top <?php echo $clase_navbar_extra; ?>"> <?php echo $this->mfunciones_cobranzas->getContenidoNavApp($estructura_id, $vista_actual, "unidad_familiar"); ?> </nav>

    <div class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">
    
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <div class="panel panel-default informacion_general datos_personales">
            <div class="panel-heading">DATOS PERSONALES</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'>
                    <div class='form-group'>
                        <label class='label-campo' for='registro_num_proceso'> <?php echo $this->lang->line('registro_num_proceso') . ': ' . $this->lang->line('registro_num_proceso_label'); ?></label><?php echo $arrCajasHTML['registro_num_proceso']; ?>
                    </div>
                </div>
                
                <div class='col-sm-4-aux'>
                    <div class='form-group'>
                        <label class="label-campo" for="estructura_regional"> <?php echo $this->lang->line('terceros_columna1'); ?>:</label>
                        <span style="text-shadow: 0 0 black;" class="AyudaTooltip" data-balloon-length="medium" data-balloon="<?php echo $this->lang->line('norm_agencia_ayuda'); ?>" data-balloon-pos="top"> </span>
                        <br />
                        <?php

                            if(count($arrAgencias[0]) > 0)
                            {
                                echo html_select('estructura_regional', $arrAgencias, 'estructura_regional_id', 'estructura_regional_nombre', '', $arrRespuesta[0]['codigo_agencia_fie']);
                            }
                            else
                            {
                                echo $this->lang->line('TablaNoRegistros');
                            }
                        ?>
                    </div>
                </div>
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='norm_primer_nombre'><?php echo $this->lang->line('norm_primer_nombre'); ?>:</label><?php echo $arrCajasHTML['norm_primer_nombre']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='norm_segundo_nombre'><?php echo $this->lang->line('norm_segundo_nombre'); ?>:</label><?php echo $arrCajasHTML['norm_segundo_nombre']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='norm_primer_apellido'><?php echo $this->lang->line('norm_primer_apellido'); ?>:</label><?php echo $arrCajasHTML['norm_primer_apellido']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='norm_segundo_apellido'><?php echo $this->lang->line('norm_segundo_apellido'); ?>:</label><?php echo $arrCajasHTML['norm_segundo_apellido']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='norm_cel'><?php echo $this->lang->line('norm_cel'); ?>:</label><?php echo $arrCajasHTML['norm_cel']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='norm_actividad'><?php echo $this->lang->line('norm_actividad'); ?>:</label><?php echo $arrCajasHTML['norm_actividad']; ?></div></div>
                
                <div class='col-sm-4'><div class='form-group'><label class='label-campo' for='norm_rel_cred'><?php echo $this->lang->line('norm_rel_cred'); ?>:</label><br /><?php echo $arrCajasHTML['norm_rel_cred']; ?><?php echo $arrCajasHTML['norm_rel_cred_otro']; ?></div></div>
                
            </div>
        </div>
        
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>