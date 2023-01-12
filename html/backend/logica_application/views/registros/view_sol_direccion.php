<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios('sincombo');
    
    if($tipo_registro == 'nuevo_solcredito')
    {
        echo '$(".informacion_operacion").hide();';
    }
    
    $act_secundaria = (int)$arrRespuesta[0]['sol_actividad_secundaria'];
    
    // ESTRUCTURA PRINCIPAL
    $resources_croquis = $this->mfunciones_microcreditos->resCroquis(($act_secundaria==1 ? 3 : 2));
?>
    <?php echo $resources_croquis->croquis_js; ?>
    // Inicializar croquis __1
    var currImage__1 = null;
    var el__1 = document.getElementById("croquis__1");
    var pad__1 = new Sketchpad(el__1, {
        line: {
            color: "#f44335",
            size: 1
        }
    });
    
    // Inicializar croquis __2
    var currImage__2 = null;
    var el__2 = document.getElementById("croquis__2");
    var pad__2 = new Sketchpad(el__2, {
        line: {
            color: "#f44335",
            size: 1
        }
    });
    
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
            
            $("#sol_trabajo_lugar_otro, #sol_trabajo_realiza_otro, #sol_dom_tipo_otro<?php echo ($act_secundaria==1 ? ', #sol_trabajo_lugar_otro, #sol_trabajo_realiza_otro' : ''); ?>").attr("placeholder", " :: Registrar otro valor ::");
            
        });
        
        $(document).ready(function() {
            $("#sol_dir_referencia, #sol_dir_referencia_dom<?php echo ($act_secundaria==1 ? ', #sol_dir_referencia_sec' : ''); ?>").togglebutton();
            $("div.modal-backdrop").remove();
            
            if(parseInt($("#sol_dir_departamento").val()) != -1)
            {
                if(parseInt($("#sol_cod_barrio").val()) <= 0){ sol_dir_localidad_ciudad(); }
                if(parseInt($("#sol_dir_localidad_ciudad").val()) <= 0){ sol_dir_provincia(); }
                if(parseInt($("#sol_dir_provincia").val()) <= 0){ sol_dir_departamento(); }
            }
            
            if(parseInt($("#sol_dir_departamento_dom").val()) != -1)
            {
                if(parseInt($("#sol_cod_barrio_dom").val()) <= 0){ sol_dir_localidad_ciudad_dom(); }
                if(parseInt($("#sol_dir_localidad_ciudad_dom").val()) <= 0){ sol_dir_provincia_dom(); }
                if(parseInt($("#sol_dir_provincia_dom").val()) <= 0){ sol_dir_departamento_dom(); }
            }
            
        });
        
        sol_dir_referencia();
        sol_dir_referencia_dom();
        
        sol_trabajo_lugar();
        sol_trabajo_realiza();
        sol_dom_tipo();
        
        <?php
        // Actividad Secundaria
        if($act_secundaria == 1)
        {
            echo ' sol_dir_referencia_sec(); sol_trabajo_lugar_sec(); sol_trabajo_realiza_sec();';
        }
        ?>
    });

    // ### REFERENCIA DIRECCIÓN SOLICITANTE  INICIO

    // Dirección Negocio
    function sol_dir_referencia()
    {
        $(".sol_dir_referencia_geo, .sol_dir_referencia_croq").hide();
            
        switch (parseInt($("#sol_dir_referencia").val())) {
            case 1:
                $(".sol_dir_referencia_geo").fadeIn();
                break;
            case 2:
                $(".sol_dir_referencia_croq").fadeIn();
                break;
            default:
                break;
        }
    }

    $("#sol_dir_referencia").on('change', function(){
        sol_dir_referencia();
    });
    
    // Dirección Domicilio
    function sol_dir_referencia_dom()
    {
        $(".sol_dir_referencia_dom_geo, .sol_dir_referencia_dom_croq").hide();
            
        switch (parseInt($("#sol_dir_referencia_dom").val())) {
            case 1:
                $(".sol_dir_referencia_dom_geo").fadeIn();
                break;
            case 2:
                $(".sol_dir_referencia_dom_croq").fadeIn();
                break;
            default:
                break;
        }
    }

    $("#sol_dir_referencia_dom").on('change', function(){
        sol_dir_referencia_dom();
    });

    // ### REFERENCIA DIRECCIÓN SOLICITANTE  FIN

    // ### MAPAS  INICIO
    
    // Solicitante GEO Negocio
    <?php $campo_geo = 'sol_croquis'; ?>
    $('<input>').attr({
        type: 'hidden',
        id: 'croquis_base64__1',
        name: 'croquis_base64__1',
        value: '<?php echo $arrRespuesta[0][$campo_geo]; ?>'
    }).appendTo('#FormularioRegistroLista');
    
    <?php $campo_geo = 'sol_geolocalizacion'; ?>
    $('<input>').attr({
        type: 'hidden',
        id: '<?php echo $campo_geo; ?>',
        name: '<?php echo $campo_geo; ?>',
        value: '<?php echo $arrRespuesta[0][$campo_geo]; ?>'
    }).appendTo('#FormularioRegistroLista');
    
    function actualizar_<?php echo $campo_geo; ?>(id) {
        document.getElementById('<?php echo $campo_geo; ?>').value = id;
    }
    
    // Solicitante GEO Domicilio
    <?php $campo_geo = 'sol_croquis_dom'; ?>
    $('<input>').attr({
        type: 'hidden',
        id: 'croquis_base64__2',
        name: 'croquis_base64__2',
        value: '<?php echo $arrRespuesta[0][$campo_geo]; ?>'
    }).appendTo('#FormularioRegistroLista');
    
    <?php $campo_geo = 'sol_geolocalizacion_dom'; ?>
    $('<input>').attr({
        type: 'hidden',
        id: '<?php echo $campo_geo; ?>',
        name: '<?php echo $campo_geo; ?>',
        value: '<?php echo $arrRespuesta[0][$campo_geo]; ?>'
    }).appendTo('#FormularioRegistroLista');
    
    function actualizar_<?php echo $campo_geo; ?>(id) {
        document.getElementById('<?php echo $campo_geo; ?>').value = id;
    }
    
    function actualizar_<?php echo $campo_geo; ?>(id) {
        document.getElementById('<?php echo $campo_geo; ?>').value = id;
    }
    
    // ### MAPAS  FIN

    // ### VALORES "OTRO" - SOLICITANTE  INICIO
    
    // sol_trabajo_lugar
    function sol_trabajo_lugar()
    {
        $("#sol_trabajo_lugar_otro").fadeOut();
            
        if(parseInt($("#sol_trabajo_lugar").val()) == 99)
        {
            $("#sol_trabajo_lugar_otro").fadeIn();
        }
    }

    $("#sol_trabajo_lugar").on('change', function(){
        sol_trabajo_lugar();
    });
    
    // sol_trabajo_realiza
    function sol_trabajo_realiza()
    {
        $("#sol_trabajo_realiza_otro").fadeOut();
            
        if(parseInt($("#sol_trabajo_realiza").val()) == 99)
        {
            $("#sol_trabajo_realiza_otro").fadeIn();
        }
    }

    $("#sol_trabajo_realiza").on('change', function(){
        sol_trabajo_realiza();
    });
    
    // sol_dom_tipo
    function sol_dom_tipo()
    {
        $("#sol_dom_tipo_otro").fadeOut();
            
        if(parseInt($("#sol_dom_tipo").val()) == 99)
        {
            $("#sol_dom_tipo_otro").fadeIn();
        }
    }

    $("#sol_dom_tipo").on('change', function(){
        sol_dom_tipo();
    });
    
    // ### VALORES "OTRO" - SOLICITANTE  FIN

    // ### DIRECCIÓN SOLICITANTE  INICIO
    
    // Dirección Negocio
    
    function sol_dir_departamento()
    {
        var parent_codigo = $("#sol_dir_departamento").val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_provincia', parent_tipo:'dir_departamento'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#sol_dir_provincia").empty();
                $("#sol_dir_provincia").append("<option value='-1'>-- Seleccione la Provincia --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#sol_dir_provincia").append("<option value='"+id+"'>"+name+"</option>");
                }
                $("#sol_dir_localidad_ciudad").empty();
                $("#sol_dir_localidad_ciudad").append("<option value='-1'>-- Antes seleccione la Provincia --</option>");
                
                $("#sol_cod_barrio").empty();
                $("#sol_cod_barrio").append("<option value='-1'>-- Antes seleccione la Localidad/ciudad --</option>");
            }
        });
    }
    
    $("#sol_dir_departamento").change(function(){
        sol_dir_departamento();
    });
    
    function sol_dir_provincia()
    {
        var parent_codigo = $("#sol_dir_provincia").val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_localidad_ciudad', parent_tipo:'dir_provincia'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#sol_dir_localidad_ciudad").empty();
                $("#sol_dir_localidad_ciudad").append("<option value='-1'>-- Seleccione la Localidad/ciudad --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#sol_dir_localidad_ciudad").append("<option value='"+id+"'>"+name+"</option>");
                }
                
                $("#sol_cod_barrio").empty();
                $("#sol_cod_barrio").append("<option value='-1'>-- Antes seleccione la Localidad/ciudad --</option>");
            }
        });
    }
    
    $("#sol_dir_provincia").change(function(){
        sol_dir_provincia();
    });
    
    function sol_dir_localidad_ciudad()
    {
        var parent_codigo = $("#sol_dir_localidad_ciudad").val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_barrio_zona_uv', parent_tipo:'dir_localidad_ciudad'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#sol_cod_barrio").empty();
                $("#sol_cod_barrio").append("<option value='-1'>-- Seleccione el Barrio/zona/uv --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#sol_cod_barrio").append("<option value='"+id+"'>"+name+"</option>");
                }
            }
        });
    }
    
    $("#sol_dir_localidad_ciudad").change(function(){
        sol_dir_localidad_ciudad();
    });

    // Dirección Domicilio
    
    function sol_dir_departamento_dom()
    {
        var parent_codigo = $("#sol_dir_departamento_dom").val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_provincia', parent_tipo:'dir_departamento'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#sol_dir_provincia_dom").empty();
                $("#sol_dir_provincia_dom").append("<option value='-1'>-- Seleccione la Provincia --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#sol_dir_provincia_dom").append("<option value='"+id+"'>"+name+"</option>");
                }
                $("#sol_dir_localidad_ciudad_dom").empty();
                $("#sol_dir_localidad_ciudad_dom").append("<option value='-1'>-- Antes seleccione la Provincia --</option>");
                
                $("#sol_cod_barrio_dom").empty();
                $("#sol_cod_barrio_dom").append("<option value='-1'>-- Antes seleccione la Localidad/ciudad --</option>");
            }
        });
    }
    
    $("#sol_dir_departamento_dom").change(function(){
        sol_dir_departamento_dom();
    });
    
    function sol_dir_provincia_dom()
    {
        var parent_codigo = $("#sol_dir_provincia_dom").val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_localidad_ciudad', parent_tipo:'dir_provincia'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#sol_dir_localidad_ciudad_dom").empty();
                $("#sol_dir_localidad_ciudad_dom").append("<option value='-1'>-- Seleccione la Localidad/ciudad --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#sol_dir_localidad_ciudad_dom").append("<option value='"+id+"'>"+name+"</option>");
                }
                
                $("#sol_cod_barrio_dom").empty();
                $("#sol_cod_barrio_dom").append("<option value='-1'>-- Antes seleccione la Localidad/ciudad --</option>");
            }
        });
    }
    
    $("#sol_dir_provincia_dom").change(function(){
        sol_dir_provincia_dom();
    });
    
    function sol_dir_localidad_ciudad_dom()
    {
        var parent_codigo = $("#sol_dir_localidad_ciudad_dom").val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_barrio_zona_uv', parent_tipo:'dir_localidad_ciudad'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#sol_cod_barrio_dom").empty();
                $("#sol_cod_barrio_dom").append("<option value='-1'>-- Seleccione el Barrio/zona/uv --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#sol_cod_barrio_dom").append("<option value='"+id+"'>"+name+"</option>");
                }
            }
        });
    }
    
    $("#sol_dir_localidad_ciudad_dom").change(function(){
        sol_dir_localidad_ciudad_dom();
    });
    
    // ### DIRECCIÓN SOLICITANTE  FIN
    
    
    <?php
    // Actividad Secundaria
    if($act_secundaria == 1)
    {
    ?>
    
        // ==### ACTIVIDAD SECUNDARIA - INICIO

        // Inicializar croquis __3
        var currImage__3 = null;
        var el__3 = document.getElementById("croquis__3");
        var pad__3 = new Sketchpad(el__3, {
            line: {
                color: "#f44335",
                size: 1
            }
        });

        // ### REFERENCIA DIRECCIÓN  INICIO

        // Dirección Negocio Actividad Secundaria
        function sol_dir_referencia_sec()
        {
            $(".sol_dir_referencia_geo_sec, .sol_dir_referencia_croq_sec").hide();

            switch (parseInt($("#sol_dir_referencia_sec").val())) {
                case 1:
                    $(".sol_dir_referencia_geo_sec").fadeIn();
                    break;
                case 2:
                    $(".sol_dir_referencia_croq_sec").fadeIn();
                    break;
                default:
                    break;
            }
        }

        $("#sol_dir_referencia_sec").on('change', function(){
            sol_dir_referencia_sec();
        });

        // ### REFERENCIA DIRECCIÓN  FIN

        // ### MAPAS  INICIO

        // Solicitante GEO Negocio Actividad Secundaria
        <?php $campo_geo = 'sol_croquis_sec'; ?>
        $('<input>').attr({
            type: 'hidden',
            id: 'croquis_base64__3',
            name: 'croquis_base64__3',
            value: '<?php echo $arrRespuesta[0][$campo_geo]; ?>'
        }).appendTo('#FormularioRegistroLista');

        <?php $campo_geo = 'sol_geolocalizacion_sec'; ?>
        $('<input>').attr({
            type: 'hidden',
            id: '<?php echo $campo_geo; ?>',
            name: '<?php echo $campo_geo; ?>',
            value: '<?php echo $arrRespuesta[0][$campo_geo]; ?>'
        }).appendTo('#FormularioRegistroLista');

        function actualizar_<?php echo $campo_geo; ?>(id) {
            document.getElementById('<?php echo $campo_geo; ?>').value = id;
        }

        // ### MAPAS  FIN

        // ### VALORES "OTRO" - INICIO

        // sol_trabajo_lugar_sec
        function sol_trabajo_lugar_sec()
        {
            $("#sol_trabajo_lugar_otro_sec").fadeOut();

            if(parseInt($("#sol_trabajo_lugar_sec").val()) == 99)
            {
                $("#sol_trabajo_lugar_otro_sec").fadeIn();
            }
        }

        $("#sol_trabajo_lugar_sec").on('change', function(){
            sol_trabajo_lugar_sec();
        });

        // sol_trabajo_realiza_sec
        function sol_trabajo_realiza_sec()
        {
            $("#sol_trabajo_realiza_otro_sec").fadeOut();

            if(parseInt($("#sol_trabajo_realiza_sec").val()) == 99)
            {
                $("#sol_trabajo_realiza_otro_sec").fadeIn();
            }
        }

        $("#sol_trabajo_realiza_sec").on('change', function(){
            sol_trabajo_realiza_sec();
        });

        // ### VALORES "OTRO" - FIN

        // ### DIRECCIÓN SOLICITANTE  INICIO

        // Dirección Negocio

        function sol_dir_departamento_sec()
        {
            var parent_codigo = $("#sol_dir_departamento_sec").val();
            $.ajax({
                url: '../../Afiliador/Select/Cargar',
                type: 'post',
                data: {
                    parent_codigo:parent_codigo, tipo:'dir_provincia', parent_tipo:'dir_departamento'
                },
                dataType: 'json',
                success:function(response){
                    var len = response.length;
                    $("#sol_dir_provincia_sec").empty();
                    $("#sol_dir_provincia_sec").append("<option value='-1'>-- Seleccione la Provincia --</option>");
                    for( var i = 0; i<len; i++){
                        var id = response[i]['lista_codigo'];
                        var name = response[i]['lista_valor'];
                        $("#sol_dir_provincia_sec").append("<option value='"+id+"'>"+name+"</option>");
                    }
                    $("#sol_dir_localidad_ciudad_sec").empty();
                    $("#sol_dir_localidad_ciudad_sec").append("<option value='-1'>-- Antes seleccione la Provincia --</option>");

                    $("#sol_cod_barrio_sec").empty();
                    $("#sol_cod_barrio_sec").append("<option value='-1'>-- Antes seleccione la Localidad/ciudad --</option>");
                }
            });
        }

        $("#sol_dir_departamento_sec").change(function(){
            sol_dir_departamento_sec();
        });

        function sol_dir_provincia_sec()
        {
            var parent_codigo = $("#sol_dir_provincia_sec").val();
            $.ajax({
                url: '../../Afiliador/Select/Cargar',
                type: 'post',
                data: {
                    parent_codigo:parent_codigo, tipo:'dir_localidad_ciudad', parent_tipo:'dir_provincia'
                },
                dataType: 'json',
                success:function(response){
                    var len = response.length;
                    $("#sol_dir_localidad_ciudad_sec").empty();
                    $("#sol_dir_localidad_ciudad_sec").append("<option value='-1'>-- Seleccione la Localidad/ciudad --</option>");
                    for( var i = 0; i<len; i++){
                        var id = response[i]['lista_codigo'];
                        var name = response[i]['lista_valor'];
                        $("#sol_dir_localidad_ciudad_sec").append("<option value='"+id+"'>"+name+"</option>");
                    }

                    $("#sol_cod_barrio_sec").empty();
                    $("#sol_cod_barrio_sec").append("<option value='-1'>-- Antes seleccione la Localidad/ciudad --</option>");
                }
            });
        }

        $("#sol_dir_provincia_sec").change(function(){
            sol_dir_provincia_sec();
        });

        function sol_dir_localidad_ciudad_sec()
        {
            var parent_codigo = $("#sol_dir_localidad_ciudad_sec").val();
            $.ajax({
                url: '../../Afiliador/Select/Cargar',
                type: 'post',
                data: {
                    parent_codigo:parent_codigo, tipo:'dir_barrio_zona_uv', parent_tipo:'dir_localidad_ciudad'
                },
                dataType: 'json',
                success:function(response){
                    var len = response.length;
                    $("#sol_cod_barrio_sec").empty();
                    $("#sol_cod_barrio_sec").append("<option value='-1'>-- Seleccione el Barrio/zona/uv --</option>");
                    for( var i = 0; i<len; i++){
                        var id = response[i]['lista_codigo'];
                        var name = response[i]['lista_valor'];
                        $("#sol_cod_barrio_sec").append("<option value='"+id+"'>"+name+"</option>");
                    }
                }
            });
        }

        $("#sol_dir_localidad_ciudad_sec").change(function(){
            sol_dir_localidad_ciudad_sec();
        });

        // ### DIRECCIÓN SOLICITANTE  FIN

        // ==### ACTIVIDAD SECUNDARIA - FIN
    
    <?php
    }
    ?>
    
</script>

    <!-- CSS CORQUIS ––––––––––––––––––––––––––––––––––––––––––––––––––--->
    <style>
        <?php echo $resources_croquis->croquis_css; ?>
    </style>

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
        
        <div class="panel panel-default informacion_general">
            <div class="panel-heading">DIRECCIÓN DEL NEGOCIO/ACTIVIDAD O LUGAR DE TRABAJO</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_dir_departamento'><?php echo $this->lang->line('sol_dir_departamento'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_dir_departamento', $arrRespuesta[0]['sol_dir_departamento'], 'dir_departamento'); ?></div></div>
                <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='sol_dir_provincia'><?php echo $this->lang->line('sol_dir_provincia'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_dir_provincia', $arrRespuesta[0]['sol_dir_provincia'], 'dir_provincia', $arrRespuesta[0]['sol_dir_departamento'], 'dir_departamento'); ?></div></div>
                <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='sol_dir_localidad_ciudad'><?php echo $this->lang->line('sol_dir_localidad_ciudad'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_dir_localidad_ciudad', $arrRespuesta[0]['sol_dir_localidad_ciudad'], 'dir_localidad_ciudad', $arrRespuesta[0]['sol_dir_provincia'], 'dir_provincia'); ?></div></div>
                <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='sol_cod_barrio'><?php echo $this->lang->line('sol_cod_barrio'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_cod_barrio', $arrRespuesta[0]['sol_cod_barrio'], 'dir_barrio_zona_uv', $arrRespuesta[0]['sol_dir_localidad_ciudad'], 'dir_localidad_ciudad'); ?></div></div>
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_direccion_trabajo'><?php echo $this->lang->line('sol_direccion_trabajo'); ?>:</label><?php echo $arrCajasHTML['sol_direccion_trabajo']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_edificio_trabajo'><?php echo $this->lang->line('sol_edificio_trabajo'); ?>:</label><?php echo $arrCajasHTML['sol_edificio_trabajo']; ?></div></div>
                <div class='col-sm-4'><div class='form-group'><label class='label-campo' for='sol_numero_trabajo'><?php echo $this->lang->line('sol_numero_trabajo'); ?>:</label><?php echo $arrCajasHTML['sol_numero_trabajo']; ?></div></div>
                
                <div class='col-sm-4'><div class='form-group'><label class='label-campo' for='sol_trabajo_lugar'><?php echo $this->lang->line('sol_trabajo_lugar'); ?>:</label><br /><?php echo $arrCajasHTML['sol_trabajo_lugar']; ?><?php echo $arrCajasHTML['sol_trabajo_lugar_otro']; ?></div></div>
                <div class='col-sm-4'><div class='form-group'><label class='label-campo' for='sol_trabajo_realiza'><?php echo $this->lang->line('sol_trabajo_realiza'); ?>:</label><?php echo $arrCajasHTML['sol_trabajo_realiza']; ?><?php echo $arrCajasHTML['sol_trabajo_realiza_otro']; ?></div></div>
                
            </div>
        </div>
        
        <div class='col-sm-12' style="text-align: center;"><div class='form-group'><label class='label-campo' for='sol_dir_referencia'>Negocio/Actividad - <?php echo $this->lang->line('sol_dir_referencia'); ?>:</label><br /><?php echo $arrCajasHTML['sol_dir_referencia']; ?></div></div>
        <div style="clear: both"></div>
        <br />
        
        <div class="panel panel-default informacion_general sol_dir_referencia_geo">
            <div class="panel-heading">Dirección del Negocio/Actividad Geolocalización</div>
            <div class="panel-body">
                
                <?php $url_armado = $this->mfunciones_generales->setEnlaceRegistro($estructura_id, 0, 'sol_geolocalizacion'); ?>
                <iframe frameborder="0" scrolling="no" style="overflow-y: hidden; width: 100%; background-color: #ffffff; height: 350px;" src="<?php echo (site_url('Registros/SolMapa/?' . $url_armado));?>"> </iframe>
                
            </div>
        </div>
    
        <div class="panel panel-default informacion_general sol_dir_referencia_croq">
            <div class="panel-heading">Dirección del Negocio/Actividad Croquis</div>
            
            <div class="panel-body" style="text-align: center;">

                <!-- CONTENIDO -->
                <ul class="nav nav-tabs">
                    <li class="active" onclick="MostrarContenedorCroquis__1('1')"><a id="tab1__1" class="panel-heading" data-toggle="tab" style="font-size: 14px;"> <i class="fa fa-check-square" aria-hidden="true"></i> Resultado </a></li>
                    <li onclick="MostrarContenedorCroquis__1('2')"><a id="tab2__1" class="panel-heading" data-toggle="tab" style="font-size: 14px;"> <i class="fa fa-pencil" aria-hidden="true"></i> Dibujo </a></li>
                    <li onclick="MostrarContenedorCroquis__1('3')"><a id="tab3__1" class="panel-heading" data-toggle="tab" style="font-size: 14px;"> <i class="fa fa-upload" aria-hidden="true"></i> Subir </a></li>
                </ul>
                <!-- HTML CORQUIS ––––––––––––––––––––––––––––––––––––––––––––––––––--->
                <!-- Domicilio ––––––––––––––––––––––––––––––––––––––––––––––––––--->
                <!-- Contenedor  Resultado ––––––––––––––––––––––––––––––––––––––––––––––––––--->
                <div id="resultado__1" class="container">
                    <div class='col-sm-12'>
                        
                        <div class="card foto-video" align="center">

                            <br />
                            
                            <?php
                            if ($this->mfunciones_microcreditos->validateIMG_simple($arrRespuesta[0]['sol_croquis']) != '') {
                                echo '<img id="img_croquis__1" src="' . $arrRespuesta[0]['sol_croquis'] . '" class="responsive-img " style="display:block;">';
                            } else {
                                echo '<label class="label-campo texto-centrado" for="" id="txt_no_registrado__1"> <br /><br /> No registrado <br /><br /><br /><br /></label> <br /><img tittle="Croquis" id="img_croquis__1" class="responsive-img " style="display:block;">';
                            }
                            ?>
                            
                        </div>
                    </div>
                </div>
                <!-- Contenedor  Lienzo ––––––––––––––––––––––––––––––––––––––––––––––––––--->
                <div id="lienzo__1" class="container" style="display:none; width: auto;">
                    
                    <div class='col-sm-12'>
                        <label class="label-campo texto-centrado" style="text-transform: none;">Dibuje el croquis en el lienzo y marque "LISTO" para obtener el <u>Resultado</u>. <br /><br /><span style="color: #008ab1;"> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> EL DIBUJO ES TEMPORAL, SI NO MARCA "LISTO" NO SE GUARDARÁ. </span></label> <br/><br/>
                    </div>
                    
                    <div style="clear: both"></div>
                    
                    <div class='col-sm-6'>
                        <div>
                            <a id="undo__1" class="panel-heading" style="font-size: 14px;"><i class="fa fa-undo" aria-hidden="true"></i> Deshacer</a>
                            <a id="clear__1"  class="panel-heading" style="font-size: 14px;"><i class="fa fa-trash-o" aria-hidden="true"></i> Limpiar</a>
                            &nbsp;&nbsp;
                            <a id="cargar__1"  class="panel-heading" style="font-size: 14px;"><i class="fa fa-arrow-circle-up" aria-hidden="true"></i> LISTO </a>
                        </div>
                    </div>
                    
                    <div class='col-sm-6'>
                        
                        <div align="center">
                            <br />
                            <label id="mensaje_lienzo__1" align="center" class="label-campo texto-centrado" style="color: red;display:none;" >
                                Desea limpiar lienzo?
                            </label>
                        </div>
                        
                        <div  id="confirmacion_lienzo__1" style="display:none" align="center">
                            <a id="confirmacion__1"  class="panel-heading" style="margin-right: -15px;">Si</a>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a id="negacion__1"  class="panel-heading">No</a>
                        </div>  
                        
                    </div>
                    
                    <div style="clear: both"></div>
                    
                    <br />
                    
                    <div class='col-sm-12 croquis_dibujo' id="croquis__1" style="width: auto !important;" ></div>

                    <div style="clear: both"></div>
                </div>
                <!-- Contenedor  Imagen ––––––––––––––––––––––––––––––––––––––––––––––––––--->
                <div id="imagen__1" class="container" style="display:none">
                    <div class='col-sm-6' ><label class="label-campo texto-centrado" style="text-transform: none;">Seleccione una imagen. Si la imagen es válida se cargará automáticamente en "Resultado". <?php echo ($this->mfunciones_microcreditos->CheckIsMobile() ? '<br /><br /><i class="fa fa-info-circle" aria-hidden="true"></i> La App debe tener habilitado los permisos de Memoria (storage) y Cámara.' : ''); ?></label></div>
                    <div class='col-sm-5' align="center">
                        <label id="error-imagen__1" class="label-campo texto-centrado" style="color: #ffa500;display:none"> <i class="fa fa-refresh" aria-hidden="true"></i> Por favor intente nuevamente.<br/></label>
                        <label id="cargando-imagen__1" class="label-campo texto-centrado" style="color: #008ab1;display:none">Cargando imagen, por favor espere...<br/></label>
                        <br/>
                        <label for="image-file__1" class="custom-file-upload">
                            Subir imagen
                        </label>
                        <input id="image-file__1" type="file" accept="image/*" onchange="uploadFile__1(this)" >
                    </div>
                    <canvas id="snapshot__1" width="700" height="400" style="display: none"></canvas>

                </div>

            </div>
            
        </div>
        
        <?php
        // Actividad Secundaria
        if($act_secundaria == 1)
        {
        ?>
            <!-- Actividad Secundaria -->

            <div class='col-sm-12' style="text-align: center;"><label class='label-campo panel-heading' for='sol_actividad_principal' style="padding: 0px;"> <i class="fa fa-briefcase" aria-hidden="true"></i> ACTIVIDAD SECUNDARIA </label></div>
            <div style="clear: both"></div>


            <div class="panel panel-default informacion_general">
                <div class="panel-heading">DIRECCIÓN DEL NEGOCIO/ACTIVIDAD O LUGAR DE TRABAJO</div>
                <div class="panel-body">

                    <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_dir_departamento_sec'><?php echo $this->lang->line('sol_dir_departamento_sec'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_dir_departamento_sec', $arrRespuesta[0]['sol_dir_departamento_sec'], 'dir_departamento'); ?></div></div>
                    <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='sol_dir_provincia_sec'><?php echo $this->lang->line('sol_dir_provincia_sec'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_dir_provincia_sec', $arrRespuesta[0]['sol_dir_provincia_sec'], 'dir_provincia', $arrRespuesta[0]['sol_dir_departamento_sec'], 'dir_departamento'); ?></div></div>
                    <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='sol_dir_localidad_ciudad_sec'><?php echo $this->lang->line('sol_dir_localidad_ciudad_sec'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_dir_localidad_ciudad_sec', $arrRespuesta[0]['sol_dir_localidad_ciudad_sec'], 'dir_localidad_ciudad', $arrRespuesta[0]['sol_dir_provincia_sec'], 'dir_provincia'); ?></div></div>
                    <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='sol_cod_barrio_sec'><?php echo $this->lang->line('sol_cod_barrio_sec'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_cod_barrio_sec', $arrRespuesta[0]['sol_cod_barrio_sec'], 'dir_barrio_zona_uv', $arrRespuesta[0]['sol_dir_localidad_ciudad_sec'], 'dir_localidad_ciudad'); ?></div></div>

                    <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_direccion_trabajo_sec'><?php echo $this->lang->line('sol_direccion_trabajo_sec'); ?>:</label><?php echo $arrCajasHTML['sol_direccion_trabajo_sec']; ?></div></div>
                    <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_edificio_trabajo_sec'><?php echo $this->lang->line('sol_edificio_trabajo_sec'); ?>:</label><?php echo $arrCajasHTML['sol_edificio_trabajo_sec']; ?></div></div>
                    <div class='col-sm-4'><div class='form-group'><label class='label-campo' for='sol_numero_trabajo_sec'><?php echo $this->lang->line('sol_numero_trabajo_sec'); ?>:</label><?php echo $arrCajasHTML['sol_numero_trabajo_sec']; ?></div></div>

                    <div class='col-sm-4'><div class='form-group'><label class='label-campo' for='sol_trabajo_lugar_sec'><?php echo $this->lang->line('sol_trabajo_lugar_sec'); ?>:</label><br /><?php echo $arrCajasHTML['sol_trabajo_lugar_sec']; ?><?php echo $arrCajasHTML['sol_trabajo_lugar_otro_sec']; ?></div></div>
                    <div class='col-sm-4'><div class='form-group'><label class='label-campo' for='sol_trabajo_realiza_sec'><?php echo $this->lang->line('sol_trabajo_realiza_sec'); ?>:</label><?php echo $arrCajasHTML['sol_trabajo_realiza_sec']; ?><?php echo $arrCajasHTML['sol_trabajo_realiza_otro_sec']; ?></div></div>

                </div>
            </div>

            <div class='col-sm-12' style="text-align: center;"><div class='form-group'><label class='label-campo' for='sol_dir_referencia_sec'>Negocio/Actividad - <?php echo $this->lang->line('sol_dir_referencia_sec'); ?>:</label><br /><?php echo $arrCajasHTML['sol_dir_referencia_sec']; ?></div></div>
            <div style="clear: both"></div>
            <br />

            <div class="panel panel-default informacion_general sol_dir_referencia_geo_sec">
                <div class="panel-heading">Dirección del Negocio/Actividad Geolocalización</div>
                <div class="panel-body">

                    <?php $url_armado = $this->mfunciones_generales->setEnlaceRegistro($estructura_id, 0, 'sol_geolocalizacion_sec'); ?>
                    <iframe frameborder="0" scrolling="no" style="overflow-y: hidden; width: 100%; background-color: #ffffff; height: 350px;" src="<?php echo (site_url('Registros/SolMapa/?' . $url_armado));?>"> </iframe>

                </div>
            </div>

            <div class="panel panel-default informacion_general sol_dir_referencia_croq_sec">
                <div class="panel-heading">Dirección del Negocio/Actividad Croquis</div>

                <div class="panel-body" style="text-align: center;">

                    <!-- CONTENIDO -->
                    <ul class="nav nav-tabs">
                        <li class="active" onclick="MostrarContenedorCroquis__3('1')"><a id="tab1__3" class="panel-heading" data-toggle="tab" style="font-size: 14px;"> <i class="fa fa-check-square" aria-hidden="true"></i> Resultado </a></li>
                        <li onclick="MostrarContenedorCroquis__3('2')"><a id="tab2__3" class="panel-heading" data-toggle="tab" style="font-size: 14px;"> <i class="fa fa-pencil" aria-hidden="true"></i> Dibujo </a></li>
                        <li onclick="MostrarContenedorCroquis__3('3')"><a id="tab3__3" class="panel-heading" data-toggle="tab" style="font-size: 14px;"> <i class="fa fa-upload" aria-hidden="true"></i> Subir </a></li>
                    </ul>
                    <!-- HTML CORQUIS ––––––––––––––––––––––––––––––––––––––––––––––––––--->
                    <!-- Domicilio ––––––––––––––––––––––––––––––––––––––––––––––––––--->
                    <!-- Contenedor  Resultado ––––––––––––––––––––––––––––––––––––––––––––––––––--->
                    <div id="resultado__3" class="container">
                        <div class='col-sm-12'>

                            <div class="card foto-video" align="center">

                                <br />

                                <?php
                                if ($this->mfunciones_microcreditos->validateIMG_simple($arrRespuesta[0]['sol_croquis_sec']) != '') {
                                    echo '<img id="img_croquis__3" src="' . $arrRespuesta[0]['sol_croquis_sec'] . '" class="responsive-img " style="display:block;">';
                                } else {
                                    echo '<label class="label-campo texto-centrado" for="" id="txt_no_registrado__3"> <br /><br /> No registrado <br /><br /><br /><br /></label> <br /><img tittle="Croquis" id="img_croquis__3" class="responsive-img " style="display:block;">';
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                    <!-- Contenedor  Lienzo ––––––––––––––––––––––––––––––––––––––––––––––––––--->
                    <div id="lienzo__3" class="container" style="display:none; width: auto;">

                        <div class='col-sm-12'>
                            <label class="label-campo texto-centrado" style="text-transform: none;">Dibuje el croquis en el lienzo y marque "LISTO" para obtener el <u>Resultado</u>. <br /><br /><span style="color: #008ab1;"> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> EL DIBUJO ES TEMPORAL, SI NO MARCA "LISTO" NO SE GUARDARÁ. </span></label> <br/><br/>
                        </div>

                        <div style="clear: both"></div>

                        <div class='col-sm-6'>
                            <div>
                                <a id="undo__3" class="panel-heading" style="font-size: 14px;"><i class="fa fa-undo" aria-hidden="true"></i> Deshacer</a>
                                <a id="clear__3"  class="panel-heading" style="font-size: 14px;"><i class="fa fa-trash-o" aria-hidden="true"></i> Limpiar</a>
                                &nbsp;&nbsp;
                                <a id="cargar__3"  class="panel-heading" style="font-size: 14px;"><i class="fa fa-arrow-circle-up" aria-hidden="true"></i> LISTO </a>
                            </div>
                        </div>

                        <div class='col-sm-6'>

                            <div align="center">
                                <br />
                                <label id="mensaje_lienzo__3" align="center" class="label-campo texto-centrado" style="color: red;display:none;" >
                                    Desea limpiar lienzo?
                                </label>
                            </div>

                            <div  id="confirmacion_lienzo__3" style="display:none" align="center">
                                <a id="confirmacion__3"  class="panel-heading" style="margin-right: -15px;">Si</a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a id="negacion__3"  class="panel-heading">No</a>
                            </div>  

                        </div>

                        <div style="clear: both"></div>

                        <br />

                        <div class='col-sm-12 croquis_dibujo' id="croquis__3" style="width: auto !important;" ></div>

                        <div style="clear: both"></div>
                    </div>
                    <!-- Contenedor  Imagen ––––––––––––––––––––––––––––––––––––––––––––––––––--->
                    <div id="imagen__3" class="container" style="display:none">
                        <div class='col-sm-6' ><label class="label-campo texto-centrado" style="text-transform: none;">Seleccione una imagen. Si la imagen es válida se cargará automáticamente en "Resultado". <?php echo ($this->mfunciones_microcreditos->CheckIsMobile() ? '<br /><br /><i class="fa fa-info-circle" aria-hidden="true"></i> La App debe tener habilitado los permisos de Memoria (storage) y Cámara.' : ''); ?></label></div>
                        <div class='col-sm-5' align="center">
                            <label id="error-imagen__3" class="label-campo texto-centrado" style="color: #ffa500;display:none"> <i class="fa fa-refresh" aria-hidden="true"></i> Por favor intente nuevamente.<br/></label>
                            <label id="cargando-imagen__3" class="label-campo texto-centrado" style="color: #008ab1;display:none">Cargando imagen, por favor espere...<br/></label>
                            <br/>
                            <label for="image-file__3" class="custom-file-upload">
                                Subir imagen
                            </label>
                            <input id="image-file__3" type="file" accept="image/*" onchange="uploadFile__3(this)" >
                        </div>
                        <canvas id="snapshot__3" width="700" height="400" style="display: none"></canvas>

                    </div>

                </div>

            </div>
        
        <?php
        }
        ?>

        <div class="panel panel-default informacion_general">
            <div class="panel-heading"><i class="fa fa-home" aria-hidden="true"></i> DIRECCIÓN DEL DOMICILIO (opcional)</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_dir_departamento_dom'><?php echo $this->lang->line('sol_dir_departamento_dom'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_dir_departamento_dom', $arrRespuesta[0]['sol_dir_departamento_dom'], 'dir_departamento'); ?></div></div>
                <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='sol_dir_provincia_dom'><?php echo $this->lang->line('sol_dir_provincia_dom'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_dir_provincia_dom', $arrRespuesta[0]['sol_dir_provincia_dom'], 'dir_provincia', $arrRespuesta[0]['sol_dir_departamento_dom'], 'dir_departamento'); ?></div></div>
                <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='sol_dir_localidad_ciudad_dom'><?php echo $this->lang->line('sol_dir_localidad_ciudad_dom'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_dir_localidad_ciudad_dom', $arrRespuesta[0]['sol_dir_localidad_ciudad_dom'], 'dir_localidad_ciudad', $arrRespuesta[0]['sol_dir_provincia_dom'], 'dir_provincia'); ?></div></div>
                <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='sol_cod_barrio_dom'><?php echo $this->lang->line('sol_cod_barrio_dom'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_cod_barrio_dom', $arrRespuesta[0]['sol_cod_barrio_dom'], 'dir_barrio_zona_uv', $arrRespuesta[0]['sol_dir_localidad_ciudad_dom'], 'dir_localidad_ciudad'); ?></div></div>
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_direccion_dom'><?php echo $this->lang->line('sol_direccion_dom'); ?>:</label><?php echo $arrCajasHTML['sol_direccion_dom']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_edificio_dom'><?php echo $this->lang->line('sol_edificio_dom'); ?>:</label><?php echo $arrCajasHTML['sol_edificio_dom']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_numero_dom'><?php echo $this->lang->line('sol_numero_dom'); ?>:</label><?php echo $arrCajasHTML['sol_numero_dom']; ?></div></div>
                <div class='col-sm-7'><div class='form-group'><label class='label-campo' for='sol_dom_tipo'><?php echo $this->lang->line('sol_dom_tipo'); ?>:</label><br /><?php echo $arrCajasHTML['sol_dom_tipo']; ?><?php echo $arrCajasHTML['sol_dom_tipo_otro']; ?></div></div>

                <div style="clear: both"></div>
                
            </div>
        </div>
                
        <div class='col-sm-12' style="text-align: center;"><div class='form-group'><label class='label-campo' for='sol_dir_referencia_dom'>Domicilio - <?php echo $this->lang->line('sol_dir_referencia_dom'); ?>:</label><br /><?php echo $arrCajasHTML['sol_dir_referencia_dom']; ?></div></div>
        <div style="clear: both"></div>
        <br />
        
        <div class="panel panel-default informacion_general sol_dir_referencia_dom_geo">
            <div class="panel-heading">Dirección del Domicilio Geolocalización</div>
            <div class="panel-body">

                <div class='col-sm-12' style="text-align: center;">

                    <div class='form-group'>

                        <?php $url_armado = $this->mfunciones_generales->setEnlaceRegistro($estructura_id, 0, 'sol_geolocalizacion_dom'); ?>
                        <iframe frameborder="0" scrolling="no" style="overflow-y: hidden; width: 100%; background-color: #ffffff; height: 350px;" src="<?php echo (site_url('Registros/SolMapa/?' . $url_armado));?>"> </iframe>

                    </div>

                </div>

            </div>
        </div>

        <div class="panel panel-default informacion_general sol_dir_referencia_dom_croq">
            <div class="panel-heading">Dirección del Domicilio Croquis</div>
            <div class="panel-body" style="text-align: center;">

                <!-- CONTENIDO -->
                <ul class="nav nav-tabs">
                    <li class="active" onclick="MostrarContenedorCroquis__2('1')"><a id="tab1__2" class="panel-heading" data-toggle="tab" style="font-size: 14px;"> <i class="fa fa-check-square" aria-hidden="true"></i> Resultado </a></li>
                    <li onclick="MostrarContenedorCroquis__2('2')"><a id="tab2__2" class="panel-heading" data-toggle="tab" style="font-size: 14px;"> <i class="fa fa-pencil" aria-hidden="true"></i> Dibujo </a></li>
                    <li onclick="MostrarContenedorCroquis__2('3')"><a id="tab3__2" class="panel-heading" data-toggle="tab" style="font-size: 14px;"> <i class="fa fa-upload" aria-hidden="true"></i> Subir </a></li>
                </ul>
                <!-- HTML CORQUIS ––––––––––––––––––––––––––––––––––––––––––––––––––--->
                <!-- Domicilio ––––––––––––––––––––––––––––––––––––––––––––––––––--->
                <!-- Contenedor  Resultado ––––––––––––––––––––––––––––––––––––––––––––––––––--->
                <div id="resultado__2" class="container">
                    <div class='col-sm-12'>
                        
                        <div class="card foto-video" align="center">

                            <br />
                            
                            <?php
                            if ($this->mfunciones_microcreditos->validateIMG_simple($arrRespuesta[0]['sol_croquis_dom']) != '') {
                                echo '<img id="img_croquis__2" src="' . $arrRespuesta[0]['sol_croquis_dom'] . '" class="responsive-img " style="display:block;">';
                            } else {
                                echo '<label class="label-campo texto-centrado" for="" id="txt_no_registrado__2"> <br /><br /> No registrado <br /><br /><br /><br /></label> <br /><img tittle="Croquis" id="img_croquis__2" class="responsive-img " style="display:block;">';
                            }
                            ?>
                            
                        </div>
                    </div>
                </div>
                <!-- Contenedor  Lienzo ––––––––––––––––––––––––––––––––––––––––––––––––––--->
                <div id="lienzo__2" class="container" style="display:none; width: auto;">
                    
                    <div class='col-sm-12'>
                        <label class="label-campo texto-centrado" style="text-transform: none;">Dibuje el croquis en el lienzo y marque "LISTO" para obtener el <u>Resultado</u>. <br /><br /><span style="color: #008ab1;"> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> EL DIBUJO ES TEMPORAL, SI NO MARCA "LISTO" NO SE GUARDARÁ. </span></label> <br/><br/>
                    </div>
                    
                    <div style="clear: both"></div>
                    
                    <div class='col-sm-6'>
                        <div>
                            <a id="undo__2" class="panel-heading" style="font-size: 14px;"><i class="fa fa-undo" aria-hidden="true"></i> Deshacer</a>
                            <a id="clear__2"  class="panel-heading" style="font-size: 14px;"><i class="fa fa-trash-o" aria-hidden="true"></i> Limpiar</a>
                            &nbsp;&nbsp;
                            <a id="cargar__2"  class="panel-heading" style="font-size: 14px;"><i class="fa fa-arrow-circle-up" aria-hidden="true"></i> LISTO </a>
                        </div>
                    </div>
                    
                    <div class='col-sm-6'>
                        
                        <div align="center">
                            <br />
                            <label id="mensaje_lienzo__2" align="center" class="label-campo texto-centrado" style="color: red;display:none;" >
                                Desea limpiar lienzo?
                            </label>
                        </div>
                        
                        <div  id="confirmacion_lienzo__2" style="display:none" align="center">
                            <a id="confirmacion__2"  class="panel-heading" style="margin-right: -15px;">Si</a>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a id="negacion__2"  class="panel-heading">No</a>
                        </div>  
                        
                    </div>
                    
                    <div style="clear: both"></div>
                    
                    <br />
                    
                    <div class='col-sm-12 croquis_dibujo' id="croquis__2" style="width: auto !important;" ></div>

                    <div style="clear: both"></div>
                </div>
                <!-- Contenedor  Imagen ––––––––––––––––––––––––––––––––––––––––––––––––––--->
                <div id="imagen__2" class="container" style="display:none">
                    <div class='col-sm-6' ><label class="label-campo texto-centrado" style="text-transform: none;">Seleccione una imagen. Si la imagen es válida se cargará automáticamente en "Resultado". <?php echo ($this->mfunciones_microcreditos->CheckIsMobile() ? '<br /><br /><i class="fa fa-info-circle" aria-hidden="true"></i> La App debe tener habilitado los permisos de Memoria (storage) y Cámara.' : ''); ?></label></div>
                    <div class='col-sm-5' align="center">
                        <label id="error-imagen__2" class="label-campo texto-centrado" style="color: #ffa500;display:none"> <i class="fa fa-refresh" aria-hidden="true"></i> Por favor intente nuevamente.<br/></label>
                        <label id="cargando-imagen__2" class="label-campo texto-centrado" style="color: #008ab1;display:none">Cargando imagen, por favor espere...<br/></label>
                        <br/>
                        <label for="image-file__2" class="custom-file-upload">
                            Subir imagen
                        </label>
                        <input id="image-file__2" type="file" accept="image/*" onchange="uploadFile__2(this)" >
                    </div>
                    <canvas id="snapshot__2" width="700" height="400" style="display: none"></canvas>

                </div>

            </div>
        </div>
        
    </div>
    
</form>

<div id="divErrorBusqueda" class="mensajeBD"> </div>