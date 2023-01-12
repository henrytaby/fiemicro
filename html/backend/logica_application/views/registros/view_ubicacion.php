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
        $("#ddc_ciudadania_usa").togglebutton();
        
        $("div.modal-backdrop").remove();
        
        $(document).ready(function(){ 
            $('.informacion_general').find("input[type=text]").each(function(ev)
            {
                $(this).attr("placeholder", " :: Registrar ::");
            });
            
            depende();
            
        });
    });

    function depende() {
        
        $(".depende").hide();
        
        if($("#dir_departamento").val() != '14')
        {
             $(".depende").show();
        }
    }

    // Carga de Combos
    
    $("#dir_departamento").change(function(){
        depende();
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
                $("#dir_provincia").empty();
                $("#dir_provincia").append("<option value='-1'>-- Seleccione la Provincia --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#dir_provincia").append("<option value='"+id+"'>"+name+"</option>");
                }
                $("#dir_localidad_ciudad").empty();
                $("#dir_localidad_ciudad").append("<option value='-1'>-- Antes seleccione la Provincia --</option>");
                
                $("#dir_barrio_zona_uv").empty();
                $("#dir_barrio_zona_uv").append("<option value='-1'>-- Antes seleccione la Localidad/ciudad --</option>");
            }
        });
    });
    
    $("#dir_provincia").change(function(){
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
                $("#dir_localidad_ciudad").empty();
                $("#dir_localidad_ciudad").append("<option value='-1'>-- Seleccione la Localidad/ciudad --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#dir_localidad_ciudad").append("<option value='"+id+"'>"+name+"</option>");
                }
                
                $("#dir_barrio_zona_uv").empty();
                $("#dir_barrio_zona_uv").append("<option value='-1'>-- Antes seleccione la Localidad/ciudad --</option>");
            }
        });
    });
    
    $("#dir_localidad_ciudad").change(function(){
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
                $("#dir_barrio_zona_uv").empty();
                $("#dir_barrio_zona_uv").append("<option value='-1'>-- Seleccione el Barrio/zona/uv --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#dir_barrio_zona_uv").append("<option value='"+id+"'>"+name+"</option>");
                }
            }
        });
    });
    
    
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
            }
        });
    });

    function actualizar_geo_dom(id) {
        document.getElementById('coordenadas_geo_dom').value = id;
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
    
    <input type="hidden" name="coordenadas_geo_dom" id="coordenadas_geo_dom" value="<?php echo $arrRespuesta[0]['coordenadas_geo_dom']; ?>" />
    
    <nav class="navbar sticky-top <?php echo $clase_navbar_extra; ?>"> <?php echo $this->mfunciones_generales->getContenidoNavApp($estructura_id, $vista_actual, "unidad_familiar"); ?> </nav>

    <div class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">
    
        <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <div class="panel panel-default informacion_general">
            <div class="panel-heading">DIRECCIÓN DOMICILIO</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux' style="display: none;"><div class='form-group'><label class='label-campo' for='dir_tipo_direccion'><?php echo $this->lang->line('dir_tipo_direccion'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('dir_tipo_direccion', ($arrRespuesta[0]['dir_tipo_direccion']=='' ? 'RE' : $arrRespuesta[0]['dir_tipo_direccion']), 'dir_tipo_direccion'); ?></div></div>
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='dir_departamento'><?php echo $this->lang->line('dir_departamento'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('dir_departamento', $arrRespuesta[0]['dir_departamento'], 'dir_departamento'); ?></div></div>
                <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='dir_provincia'><?php echo $this->lang->line('dir_provincia'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('dir_provincia', $arrRespuesta[0]['dir_provincia'], 'dir_provincia', $arrRespuesta[0]['dir_departamento'], 'dir_departamento'); ?></div></div>
                <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='dir_localidad_ciudad'><?php echo $this->lang->line('dir_localidad_ciudad'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('dir_localidad_ciudad', $arrRespuesta[0]['dir_localidad_ciudad'], 'dir_localidad_ciudad', $arrRespuesta[0]['dir_provincia'], 'dir_provincia'); ?></div></div>
                <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='dir_barrio_zona_uv'><?php echo $this->lang->line('dir_barrio_zona_uv'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('dir_barrio_zona_uv', $arrRespuesta[0]['dir_barrio_zona_uv'], 'dir_barrio_zona_uv', $arrRespuesta[0]['dir_localidad_ciudad'], 'dir_localidad_ciudad'); ?></div></div>
                
                <div class='col-sm-4-aux' style="display: none;"><div class='form-group'><label class='label-campo' for='dir_ubicacionreferencial'><?php echo $this->lang->line('dir_ubicacionreferencial'); ?>:</label><?php echo $arrCajasHTML['dir_ubicacionreferencial']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='dir_av_calle_pasaje'><?php echo $this->lang->line('dir_av_calle_pasaje'); ?>:</label><?php echo $arrCajasHTML['dir_av_calle_pasaje']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='dir_edif_cond_urb'><?php echo $this->lang->line('dir_edif_cond_urb'); ?>:</label><?php echo $arrCajasHTML['dir_edif_cond_urb']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='dir_numero'><?php echo $this->lang->line('dir_numero'); ?>:</label><?php echo $arrCajasHTML['dir_numero']; ?></div></div>
                
            </div>
        </div>
        
    </div>
    
</form>
        
<div id="divErrorBusqueda" class="mensajeBD"> </div>