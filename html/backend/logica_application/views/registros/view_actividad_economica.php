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
        
        $("div.modal-backdrop").remove();
        
        ae_sector_economico();
        
        $(document).ready(function(){ 
            $('.informacion_general').find("input[type=text]").each(function(ev)
            {
                $(this).attr("placeholder", " :: Registrar ::");
            });
            
        });
    });

    function ae_sector_economico() {
        
        $(".panel_empleos").hide();
        
        if($("#ae_sector_economico").val() == 'V' && $("#ae_actividad_ocupacion").val() != '99001')
        {
            $(".panel_empleos").fadeIn();
        }
        else
        {
            $(".panel_empleos").fadeOut();
        }
        
        panel_negocio();
    }

    function panel_negocio() {
        
        $(".panel_negocio").hide();
        
        if($("#ae_sector_economico").val() == 'I' || $("#ae_sector_economico").val() == 'II' || $("#ae_sector_economico").val() == 'III' || $("#ae_sector_economico").val() == 'IV')
        {
            $(".panel_negocio").fadeIn();
        }
        else
        {
            $(".panel_negocio").fadeOut();
        }
    }

    $("#ae_sector_economico").change(function(){
        ae_sector_economico();
    });

    // Carga de Combos
    
    $("#ae_sector_economico").change(function(){
        var parent_codigo = $(this).val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'ae_subsector_economico', parent_tipo:'ae_sector_economico'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#ae_subsector_economico").empty();
                $("#ae_subsector_economico").append("<option value='-1'>-- Seleccione el Sub sector económico --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#ae_subsector_economico").append("<option value='"+id+"'>"+name+"</option>");
                }
                
                $("#ae_actividad_ocupacion").empty();
                $("#ae_actividad_ocupacion").append("<option value='-1'>-- Antes seleccione Sub sector economico --</option>");
                
                $("#ae_actividad_fie").empty();
                $("#ae_actividad_fie").append("<option value='-1'>-- Antes seleccione Actividad Económica --</option>");
            }
        });
    });
    
    $("#ae_subsector_economico").change(function(){
        var parent_codigo = $(this).val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'ae_actividad_economica', parent_tipo:'ae_subsector_economico'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#ae_actividad_ocupacion").empty();
                $("#ae_actividad_ocupacion").append("<option value='-1'>-- Seleccione la Actividad Econímica --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#ae_actividad_ocupacion").append("<option value='"+id+"'>"+name+"</option>");
                }
                
                $("#ae_actividad_fie").empty();
                $("#ae_actividad_fie").append("<option value='-1'>-- Antes seleccione Actividad Económica --</option>");
            }
        });
    });
    
    $("#ae_actividad_ocupacion").change(function(){
        var parent_codigo = $(this).val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'ae_actividad_fie', parent_tipo:'ae_actividad_economica'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#ae_actividad_fie").empty();
                $("#ae_actividad_fie").append("<option value='-1'>-- Seleccione la Actividad FIE --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#ae_actividad_fie").append("<option value='"+id+"'>"+name+"</option>");
                }
            }
        });
        
        ae_sector_economico();
    });

    $("#emp_codigo_actividad").combobox();
    $('.custom-combobox-input').attr("placeholder", "--Seleccione el valor o escriba para filtrar--");

    // Carga de Combos
    
    $("#dir_departamento_neg").change(function(){
        
        var parent_codigo = $(this).val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_provincia', parent_tipo:'dir_departamento'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#dir_provincia_neg").empty();
                $("#dir_provincia_neg").append("<option value='-1'>-- Seleccione la Provincia --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#dir_provincia_neg").append("<option value='"+id+"'>"+name+"</option>");
                }
                $("#dir_localidad_ciudad_neg").empty();
                $("#dir_localidad_ciudad_neg").append("<option value='-1'>-- Antes seleccione la Provincia --</option>");
                
                $("#dir_barrio_zona_uv_neg").empty();
                $("#dir_barrio_zona_uv_neg").append("<option value='-1'>-- Antes seleccione la Localidad/ciudad --</option>");
            }
        });
    });
    
    $("#dir_provincia_neg").change(function(){
        var parent_codigo = $(this).val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_localidad_ciudad', parent_tipo:'dir_provincia'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#dir_localidad_ciudad_neg").empty();
                $("#dir_localidad_ciudad_neg").append("<option value='-1'>-- Seleccione la Localidad/ciudad --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#dir_localidad_ciudad_neg").append("<option value='"+id+"'>"+name+"</option>");
                }
                
                $("#dir_barrio_zona_uv_neg").empty();
                $("#dir_barrio_zona_uv_neg").append("<option value='-1'>-- Antes seleccione la Localidad/ciudad --</option>");
            }
        });
    });
    
    $("#dir_localidad_ciudad_neg").change(function(){
        var parent_codigo = $(this).val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_barrio_zona_uv', parent_tipo:'dir_localidad_ciudad'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#dir_barrio_zona_uv_neg").empty();
                $("#dir_barrio_zona_uv_neg").append("<option value='-1'>-- Seleccione el Barrio/zona/uv --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#dir_barrio_zona_uv_neg").append("<option value='"+id+"'>"+name+"</option>");
                }
            }
        });
    });

    function actualizar_geo_trab(id) {
        document.getElementById('coordenadas_geo_trab').value = id;
    }

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
    
    <input type="hidden" name="coordenadas_geo_trab" id="coordenadas_geo_trab" value="<?php echo $arrRespuesta[0]['coordenadas_geo_trab']; ?>" />
    
    <nav class="navbar sticky-top <?php echo $clase_navbar_extra; ?>"> <?php echo $this->mfunciones_generales->getContenidoNavApp($estructura_id, $vista_actual, "unidad_familiar"); ?> </nav>

    <div class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">
    
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <div class="panel panel-default informacion_general">
            <div class="panel-heading">ACTIVIDAD ECONÓMICA</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='ae_sector_economico'><?php echo $this->lang->line('ae_sector_economico'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('ae_sector_economico', $arrRespuesta[0]['ae_sector_economico'], 'ae_sector_economico'); ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='ae_subsector_economico'><?php echo $this->lang->line('ae_subsector_economico'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('ae_subsector_economico', $arrRespuesta[0]['ae_subsector_economico'], 'ae_subsector_economico', $arrRespuesta[0]['ae_sector_economico'], 'ae_sector_economico'); ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='ae_actividad_ocupacion'><?php echo $this->lang->line('ae_actividad_ocupacion'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('ae_actividad_ocupacion', $arrRespuesta[0]['ae_actividad_ocupacion'], 'ae_actividad_economica', $arrRespuesta[0]['ae_subsector_economico'], 'ae_subsector_economico'); ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='ae_actividad_fie'><?php echo $this->lang->line('ae_actividad_fie'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('ae_actividad_fie', $arrRespuesta[0]['ae_actividad_fie'], 'ae_actividad_fie', $arrRespuesta[0]['ae_actividad_ocupacion'], 'ae_actividad_economica'); ?></div></div>
                
                <div class='col-sm-4-aux' style="display: none;"><div class='form-group'><label class='label-campo' for='ae_ambiente'><?php echo $this->lang->line('ae_ambiente'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('ae_ambiente', '9', 'ae_ambiente'); ?></div></div>
                
            </div>
        </div>
        
        <div class="panel panel-default informacion_general panel_negocio">
            <div class="panel-heading">LUGAR DONDE REALIZA LA ACTIVIDAD ECONÓMICA</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux' style="display: none;"><div class='form-group'><label class='label-campo' for='dir_tipo_direccion'><?php echo $this->lang->line('dir_tipo_direccion'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('dir_tipo_direccion', ($arrRespuesta[0]['dir_tipo_direccion']=='' ? 'RE' : $arrRespuesta[0]['dir_tipo_direccion']), 'dir_tipo_direccion'); ?></div></div>
                
                <?php
                
                // Si no se tiene registrado la direccion geografica del Negocio, se toma los valores del domicilio
                
                
                // SI SE DECIDE QUE SE MUESTRE Y SELECCIONE EL DEPARTAMENTO INGRESAR POR TRUE
                if(1 == 1)
                {
                    // BLOQUE 1
                    if($arrRespuesta[0]['dir_departamento_neg'] == '')
                    {
                        $arrRespuesta[0]['dir_departamento_neg'] = $arrRespuesta[0]['dir_departamento'];
                        $arrRespuesta[0]['dir_provincia_neg'] = $arrRespuesta[0]['dir_provincia'];
                        $arrRespuesta[0]['dir_localidad_ciudad_neg'] = $arrRespuesta[0]['dir_localidad_ciudad'];
                    }
                }
                else
                {
                    // BLOQUE 2
                    echo '<script type="text/javascript"> $(".ubicacion_negocio").hide(); </script>';

                    $arrRespuesta[0]['dir_departamento_neg'] = $arrRespuesta[0]['dir_departamento'];
                    $arrRespuesta[0]['dir_provincia_neg'] = $arrRespuesta[0]['dir_provincia'];
                    $arrRespuesta[0]['dir_localidad_ciudad_neg'] = $arrRespuesta[0]['dir_localidad_ciudad'];
                }
                
                ?>
                
                <div class='col-sm-4-aux ubicacion_negocio'><div class='form-group'><label class='label-campo' for='dir_departamento'><?php echo $this->lang->line('dir_departamento'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('dir_departamento_neg', $arrRespuesta[0]['dir_departamento_neg'], 'dir_departamento'); ?></div></div>
                <div class='col-sm-4-aux ubicacion_negocio'><div class='form-group'><label class='label-campo' for='dir_provincia'><?php echo $this->lang->line('dir_provincia'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('dir_provincia_neg', $arrRespuesta[0]['dir_provincia_neg'], 'dir_provincia', $arrRespuesta[0]['dir_departamento_neg'], 'dir_departamento'); ?></div></div>
                <div class='col-sm-4-aux ubicacion_negocio'><div class='form-group'><label class='label-campo' for='dir_localidad_ciudad'><?php echo $this->lang->line('dir_localidad_ciudad'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('dir_localidad_ciudad_neg', $arrRespuesta[0]['dir_localidad_ciudad_neg'], 'dir_localidad_ciudad', $arrRespuesta[0]['dir_provincia_neg'], 'dir_provincia'); ?></div></div>
                <div class='col-sm-4-aux dir_barrio_zona_uv'><div class='form-group'><label class='label-campo' for='dir_barrio_zona_uv'><?php echo $this->lang->line('dir_barrio_zona_uv'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('dir_barrio_zona_uv_neg', $arrRespuesta[0]['dir_barrio_zona_uv_neg'], 'dir_barrio_zona_uv', $arrRespuesta[0]['dir_localidad_ciudad_neg'], 'dir_localidad_ciudad'); ?></div></div>
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='dir_av_calle_pasaje'><?php echo $this->lang->line('dir_av_calle_pasaje'); ?>:</label><?php echo $arrCajasHTML['dir_av_calle_pasaje_neg']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='dir_edif_cond_urb'><?php echo $this->lang->line('dir_edif_cond_urb'); ?>:</label><?php echo $arrCajasHTML['dir_edif_cond_urb_neg']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='dir_numero'><?php echo $this->lang->line('dir_numero'); ?>:</label><?php echo $arrCajasHTML['dir_numero_neg']; ?></div></div>
                
            </div>
        </div>
        
        <div class="panel panel-default informacion_general panel_empleos">
            <div class="panel-heading">LUGAR DE TRABAJO</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='emp_nombre_empresa'><?php echo $this->lang->line('emp_nombre_empresa'); ?>:</label><?php echo $arrCajasHTML['emp_nombre_empresa']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='emp_direccion_trabajo'><?php echo $this->lang->line('emp_direccion_trabajo'); ?>:</label><?php echo $arrCajasHTML['emp_direccion_trabajo']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='emp_telefono_faxtrabaj'><?php echo $this->lang->line('emp_telefono_faxtrabaj'); ?>:</label><?php echo $arrCajasHTML['emp_telefono_faxtrabaj']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='emp_tipo_empresa'><?php echo $this->lang->line('emp_tipo_empresa'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('emp_tipo_empresa', $arrRespuesta[0]['emp_tipo_empresa'], 'emp_tipo_empresa'); ?></div></div>
                <div class='col-sm-4-aux' style="display: none;"><div class='form-group'><label class='label-campo' for='emp_antiguedad_empresa'><?php echo $this->lang->line('emp_antiguedad_empresa'); ?>:</label><?php echo $arrCajasHTML['emp_antiguedad_empresa']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='emp_codigo_actividad'><?php echo $this->lang->line('emp_codigo_actividad'); ?>:</label><span class="AyudaTooltip" data-balloon-length="small" data-balloon="Según CAEDEC ASFI. Debe tener relacion al registro de la actividad economica ya que generaria inconsistencia en el análisis financiero." data-balloon-pos="right"></span><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('emp_codigo_actividad', $arrRespuesta[0]['emp_codigo_actividad'], 'emp_codigo_actividad', '-1', '-1', '-1', 'SINSELECCIONAR|VACIO'); ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='emp_descripcion_cargo'><?php echo $this->lang->line('emp_descripcion_cargo'); ?>:</label><?php echo $arrCajasHTML['emp_descripcion_cargo']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='emp_fecha_ingreso'><?php echo $this->lang->line('emp_fecha_ingreso'); ?>:</label><?php echo $arrCajasHTML['emp_fecha_ingreso']; ?></div></div>
                
            </div>
        </div>
        
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>