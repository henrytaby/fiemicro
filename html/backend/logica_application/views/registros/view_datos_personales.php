<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios('sin_combo');
    
    if($tipo_registro == 'unidad_familiar')
    {
        echo '$(".informacion_operacion").hide();';
    }
?>

    $(document).ready(function() {
        $("#di_indefinido").togglebutton();
        $("#tipo_cuenta").togglebutton();
        $("#ddc_ciudadania_usa").togglebutton();
        
        $("div.modal-backdrop").remove();
        
        fecha_vencimiento();
        di_apellido_casada();
        
        $(document).ready(function(){ 
            $('.informacion_general').find("input[type=text]").each(function(ev)
            {
                $(this).attr("placeholder", " :: Registrar ::");
            });
        });
    });

    function fecha_vencimiento() {
        
        $(".di_fecha_vencimiento").hide();
        
        if($("#di_indefinido").val() == '0')
        {
            $(".di_fecha_vencimiento").show();
        }
    }

    $("#di_indefinido").change(function(){
        fecha_vencimiento();
    });

    function ddc_motivo_permanencia_usa() {
        
        $(".ddc_motivo_permanencia_usa").fadeOut();
        
        if($("#ddc_ciudadania_usa").val() == '1')
        {
            $(".ddc_motivo_permanencia_usa").fadeIn();
        }
    }
    
    $("#di_genero").change(function(){
        di_apellido_casada();
    });
    
    $("#di_estadocivil").change(function(){
        di_apellido_casada();
    });
    
    function di_apellido_casada() {
        
        if($("#di_genero").val() != 'M' && $("#di_estadocivil").val().charAt(0) != 'S')
        {
            $(".di_apellido_casada").fadeIn();
        }
        else
        {
            $(".di_apellido_casada").fadeOut();
            $("#di_apellido_casada").val("");
        }
    }

    $('#di_primernombre').attr("style","text-transform: capitalize;");
    $('#di_segundo_otrosnombres').attr("style","text-transform: capitalize;");
    $('#di_primerapellido').attr("style","text-transform: capitalize;");
    $('#di_segundoapellido').attr("style","text-transform: capitalize;");


    // Carga de Combos

</script>

    <?php

        $text_unidad_familiar = '';
        if($tipo_registro == 'unidad_familiar')
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
    <input type="hidden" name="vista_actual" id="vista_actual" value="<?php if($tipo_registro == 'unidad_familiar') { echo 'view_datos_generales'; } else { echo $vista_actual; } ?>" />
    <input type="hidden" name="home_ant_sig" id="home_ant_sig" value="" />
    
    <input type="hidden" name="tipo_registro" id="tipo_registro" value="<?php echo $tipo_registro; ?>" />
    <input type="hidden" name="ejecutivo_id" id="ejecutivo_id" value="<?php echo $arrRespuesta[0]['ejecutivo_id']; ?>" />
    
    <input type="hidden" name="general_categoria" id="general_categoria" value="1" />
    
    <nav class="navbar sticky-top <?php echo $clase_navbar_extra; ?>"> <?php echo $this->mfunciones_generales->getContenidoNavApp($estructura_id, $vista_actual, "unidad_familiar"); ?> </nav>

    <div class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">
    
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <div class="panel panel-default informacion_general">
            <div class="panel-heading texto-centrado"><?php echo $this->lang->line('tipo_cuenta'); ?></div>
            <div class="panel-body">
                
                <div class='col-sm-12' style="text-align: center;"><div class='form-group'><br /><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('tipo_cuenta', $arrRespuesta[0]['tipo_cuenta'], 'tipo_cuenta', '-1', '-1', '-1', 'SINSELECCIONAR'); ?></div></div>
        
            </div>
        </div>
        
        <div class="panel panel-default informacion_general">
            <div class="panel-heading">DECLARACION DE CIUDADANIA</div>
            <div class="panel-body">
                
                <div class='col-sm-12' style="text-align: center;"><div class='form-group'><label class='label-campo' for='ddc_ciudadania_usa'><?php echo $this->lang->line('ddc_ciudadania_usa'); ?>:</label><br /><?php echo $arrCajasHTML['ddc_ciudadania_usa']; ?></div></div>
                <div class='col-sm-12 ddc_motivo_permanencia_usa' style="display: none;"><div class='form-group'><label class='label-campo' for='ddc_motivo_permanencia_usa'><?php echo $this->lang->line('ddc_motivo_permanencia_usa'); ?>:</label><?php echo $arrCajasHTML['ddc_motivo_permanencia_usa']; ?></div></div>
                
            </div>
        </div>
        
        <div class="panel panel-default informacion_general">
            <div class="panel-heading">Cédula de identidad</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='cI_numeroraiz'><?php echo $this->lang->line('cI_numeroraiz'); ?>:</label><?php echo $arrCajasHTML['cI_numeroraiz']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='cI_complemento'><?php echo $this->lang->line('cI_complemento'); ?>:</label><?php echo $arrCajasHTML['cI_complemento']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='cI_lugar_emisionoextension'><?php echo $this->lang->line('cI_lugar_emisionoextension'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('cI_lugar_emisionoextension', $arrRespuesta[0]['cI_lugar_emisionoextension'], 'cI_lugar_emisionoextension'); ?></div></div>
                <div class='col-sm-4-aux' style="text-align: center;"><div class='form-group'><label class='label-campo' for='di_indefinido'><?php echo $this->lang->line('di_indefinido'); ?>:</label><br /><?php echo $arrCajasHTML['di_indefinido']; ?></div></div>
                <div class='col-sm-4-aux di_fecha_vencimiento'><div class='form-group'><label class='label-campo' for='di_fecha_vencimiento'><?php echo $this->lang->line('di_fecha_vencimiento'); ?>:</label><?php echo $arrCajasHTML['di_fecha_vencimiento']; ?></div></div>
                
            </div>
        </div>
        
        <div class="panel panel-default informacion_general">
            <div class="panel-heading">DATOS DE IDENTIFICACIÓN</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='di_primernombre'><?php echo $this->lang->line('di_primernombre'); ?>:</label><?php echo $arrCajasHTML['di_primernombre']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='di_segundo_otrosnombres'><?php echo $this->lang->line('di_segundo_otrosnombres'); ?>:</label><?php echo $arrCajasHTML['di_segundo_otrosnombres']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='di_primerapellido'><?php echo $this->lang->line('di_primerapellido'); ?>:</label><?php echo $arrCajasHTML['di_primerapellido']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='di_segundoapellido'><?php echo $this->lang->line('di_segundoapellido'); ?>:</label><?php echo $arrCajasHTML['di_segundoapellido']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='di_fecha_nacimiento'><?php echo $this->lang->line('di_fecha_nacimiento'); ?>:</label><?php echo $arrCajasHTML['di_fecha_nacimiento']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='di_genero'><?php echo $this->lang->line('di_genero'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('di_genero', $arrRespuesta[0]['di_genero'], 'di_genero'); ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='di_estadocivil'><?php echo $this->lang->line('di_estadocivil'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('di_estadocivil', $arrRespuesta[0]['di_estadocivil'], 'di_estadocivil'); ?></div></div>
                <div class='col-sm-4-aux di_apellido_casada' style="display: none;"><div class='form-group'><label class='label-campo' for='di_apellido_casada'><?php echo $this->lang->line('di_apellido_casada'); ?>:</label><?php echo $arrCajasHTML['di_apellido_casada']; ?></div></div>
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='direccion_email'><?php echo $this->lang->line('direccion_email'); ?>:</label><?php echo $arrCajasHTML['direccion_email']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='dir_notelefonico'><?php echo $this->lang->line('dir_notelefonico'); ?>:</label><?php echo $arrCajasHTML['dir_notelefonico']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='envio'><?php echo $this->lang->line('envio'); ?>:</label><span class="AyudaTooltip" data-balloon-length="small" data-balloon="Permite registrar a cuál ubicación el Cliente prefiere que se la envíe la Tarjeta u Otros." data-balloon-pos="right"></span><?php echo $arrCajasHTML['envio']; ?></div></div>
                
            </div>
        </div>
        
        <div class="panel panel-default informacion_general">
            <div class="panel-heading">DATOS DEMOGRÁFICOS</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='dd_profesion'><?php echo $this->lang->line('dd_profesion'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('dd_profesion', $arrRespuesta[0]['dd_profesion'], 'dd_profesion'); ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='dd_nivel_estudios'><?php echo $this->lang->line('dd_nivel_estudios'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('dd_nivel_estudios', $arrRespuesta[0]['dd_nivel_estudios'], 'dd_nivel_estudios'); ?></div></div>
                <div class='col-sm-4-aux' style="display: none;"><div class='form-group'><label class='label-campo' for='dd_dependientes'><?php echo $this->lang->line('dd_dependientes'); ?>:</label><?php echo $arrCajasHTML['dd_dependientes']; ?></div></div>
                <div class='col-sm-4-aux' style="display: none;"><div class='form-group'><label class='label-campo' for='dd_proposito_rel_comercial'><?php echo $this->lang->line('dd_proposito_rel_comercial'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('dd_proposito_rel_comercial', '1', 'dd_proposito_rel_comercial'); ?></div></div>
                
            </div>
        </div>
        
        <div class="panel panel-default informacion_general">
            <div class="panel-heading">DATOS ECONÓMICOS</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='dec_ingresos_mensuales'><?php echo $this->lang->line('dec_ingresos_mensuales'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('dec_ingresos_mensuales', $arrRespuesta[0]['dec_ingresos_mensuales'], 'dec_ingresos_mensuales'); ?></div></div>
                <div class='col-sm-4-aux' style="display: none;"><div class='form-group'><label class='label-campo' for='dec_nivel_egresos'><?php echo $this->lang->line('dec_nivel_egresos'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('dec_nivel_egresos', $arrRespuesta[0]['dec_nivel_egresos'], 'dec_nivel_egresos'); ?></div></div>
                
            </div>
        </div>
        
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>