<script type="text/javascript">
<?php 

    echo $strValidacionJqValidate;
    echo $this->mfunciones_generales->JSformularios('sincombo');
    
    if($tipo_registro == 'nuevo_solcredito')
    {
        echo '$(".informacion_operacion").hide();';
    }
?>

    // Si no fue seleccionado Cónyuge, se muestra el contenido vacío.
    if(<?php echo (int)$arrRespuesta[0]['sol_conyugue']; ?>==0)
    {
        $("#estructura_principal").html('<?php echo $this->lang->line('sol_con_sin_seleccion'); ?>');
        
        $("#home_ant_sig").val("sol_direccion");
        EnviarSinGuardar();
    }

    
    <?php
    
    if((int)$arrRespuesta[0]['sol_conyugue'] == 1)
    {
        // Sólo carga si la opción de conyuge esta marcada
        // ESTRUCTURA PRINCIPAL
        $resources_croquis = $this->mfunciones_microcreditos->resCroquis();
        echo $resources_croquis->croquis_js;
    ?>
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

    <?php
    }
    ?>

    function ShowcopyDirSol()
    {
        $("#pregunta_CopyDir").modal();
    }

    function copyDirSol()
    {
        $("#home_ant_sig").val("sol_direccion_auxcopy");
        EnviarSinGuardar();
    }

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
            
            $("#sol_con_trabajo_lugar_otro, #sol_con_trabajo_realiza_otro, #sol_con_dom_tipo_otro").attr("placeholder", " :: Registrar otro valor ::");
        });
        
        $(document).ready(function() {
            $("#sol_con_dir_referencia, #sol_con_dir_referencia_dom").togglebutton();
            $("div.modal-backdrop").remove();
            
            if(parseInt($("#sol_con_dir_departamento").val()) != -1)
            {
                if(parseInt($("#sol_con_cod_barrio").val()) <= 0){ sol_con_dir_localidad_ciudad(); }
                if(parseInt($("#sol_con_dir_localidad_ciudad").val()) <= 0){ sol_con_dir_provincia(); }
                if(parseInt($("#sol_con_dir_provincia").val()) <= 0){ sol_con_dir_departamento(); }
            }
            
            if(parseInt($("#sol_con_dir_departamento_dom").val()) != -1)
            {
                if(parseInt($("#sol_con_cod_barrio_dom").val()) <= 0){ sol_con_dir_localidad_ciudad_dom(); }
                if(parseInt($("#sol_con_dir_localidad_ciudad_dom").val()) <= 0){ sol_con_dir_provincia_dom(); }
                if(parseInt($("#sol_con_dir_provincia_dom").val()) <= 0){ sol_con_dir_departamento_dom(); }
            }
            
        });
        
        sol_con_dir_referencia();
        sol_con_dir_referencia_dom();
        
        sol_con_trabajo_lugar();
        sol_con_trabajo_realiza();
        sol_con_dom_tipo();
    });

    // ### REFERENCIA DIRECCIÓN CÓNYUGE  INICIO

    // Dirección Negocio
    function sol_con_dir_referencia()
    {
        $(".sol_con_dir_referencia_geo, .sol_con_dir_referencia_croq").hide();
            
        switch (parseInt($("#sol_con_dir_referencia").val())) {
            case 1:
                $(".sol_con_dir_referencia_geo").fadeIn();
                break;
            case 2:
                $(".sol_con_dir_referencia_croq").fadeIn();
                break;
            default:
                break;
        }
    }

    $("#sol_con_dir_referencia").on('change', function(){
        sol_con_dir_referencia();
    });
    
    // Dirección Domicilio
    function sol_con_dir_referencia_dom()
    {
        $(".sol_con_dir_referencia_dom_geo, .sol_con_dir_referencia_dom_croq").hide();
            
        switch (parseInt($("#sol_con_dir_referencia_dom").val())) {
            case 1:
                $(".sol_con_dir_referencia_dom_geo").fadeIn();
                break;
            case 2:
                $(".sol_con_dir_referencia_dom_croq").fadeIn();
                break;
            default:
                break;
        }
    }

    $("#sol_con_dir_referencia_dom").on('change', function(){
        sol_con_dir_referencia_dom();
    });

    // ### REFERENCIA DIRECCIÓN CÓNYUGE  FIN

    // ### MAPAS  INICIO
    
    // Cónyuge GEO Negocio
    <?php $campo_geo = 'sol_con_croquis'; ?>
    $('<input>').attr({
        type: 'hidden',
        id: 'croquis_base64__1',
        name: 'croquis_base64__1',
        value: '<?php echo $arrRespuesta[0][$campo_geo]; ?>'
    }).appendTo('#FormularioRegistroLista');
    
    <?php $campo_geo = 'sol_con_geolocalizacion'; ?>
    $('<input>').attr({
        type: 'hidden',
        id: '<?php echo $campo_geo; ?>',
        name: '<?php echo $campo_geo; ?>',
        value: '<?php echo $arrRespuesta[0][$campo_geo]; ?>'
    }).appendTo('#FormularioRegistroLista');
    
    function actualizar_<?php echo $campo_geo; ?>(id) {
        document.getElementById('<?php echo $campo_geo; ?>').value = id;
    }
    
    // Cónyuge GEO Domicilio
    <?php $campo_geo = 'sol_con_croquis_dom'; ?>
    $('<input>').attr({
        type: 'hidden',
        id: 'croquis_base64__2',
        name: 'croquis_base64__2',
        value: '<?php echo $arrRespuesta[0][$campo_geo]; ?>'
    }).appendTo('#FormularioRegistroLista');
    
    <?php $campo_geo = 'sol_con_geolocalizacion_dom'; ?>
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

    // ### VALORES "OTRO" - CÓNYUGE  INICIO
    
    // sol_con_trabajo_lugar
    function sol_con_trabajo_lugar()
    {
        $("#sol_con_trabajo_lugar_otro").fadeOut();
            
        if(parseInt($("#sol_con_trabajo_lugar").val()) == 99)
        {
            $("#sol_con_trabajo_lugar_otro").fadeIn();
        }
    }

    $("#sol_con_trabajo_lugar").on('change', function(){
        sol_con_trabajo_lugar();
    });
    
    // sol_con_trabajo_realiza
    function sol_con_trabajo_realiza()
    {
        $("#sol_con_trabajo_realiza_otro").fadeOut();
            
        if(parseInt($("#sol_con_trabajo_realiza").val()) == 99)
        {
            $("#sol_con_trabajo_realiza_otro").fadeIn();
        }
    }

    $("#sol_con_trabajo_realiza").on('change', function(){
        sol_con_trabajo_realiza();
    });
    
    // sol_con_dom_tipo
    function sol_con_dom_tipo()
    {
        $("#sol_con_dom_tipo_otro").fadeOut();
            
        if(parseInt($("#sol_con_dom_tipo").val()) == 99)
        {
            $("#sol_con_dom_tipo_otro").fadeIn();
        }
    }

    $("#sol_con_dom_tipo").on('change', function(){
        sol_con_dom_tipo();
    });
    
    // ### VALORES "OTRO" - SOLICITANTE  FIN

    // ### DIRECCIÓN CÓNYUGE  INICIO
    
    // Dirección Negocio
    
    function sol_con_dir_departamento()
    {
        var parent_codigo = $("#sol_con_dir_departamento").val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_provincia', parent_tipo:'dir_departamento'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#sol_con_dir_provincia").empty();
                $("#sol_con_dir_provincia").append("<option value='-1'>-- Seleccione la Provincia --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#sol_con_dir_provincia").append("<option value='"+id+"'>"+name+"</option>");
                }
                $("#sol_con_dir_localidad_ciudad").empty();
                $("#sol_con_dir_localidad_ciudad").append("<option value='-1'>-- Antes seleccione la Provincia --</option>");
                
                $("#sol_con_cod_barrio").empty();
                $("#sol_con_cod_barrio").append("<option value='-1'>-- Antes seleccione la Localidad/ciudad --</option>");
            }
        });
    }
    
    $("#sol_con_dir_departamento").change(function(){
        sol_con_dir_departamento();
    });
    
    function sol_con_dir_provincia()
    {
        var parent_codigo = $("#sol_con_dir_provincia").val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_localidad_ciudad', parent_tipo:'dir_provincia'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#sol_con_dir_localidad_ciudad").empty();
                $("#sol_con_dir_localidad_ciudad").append("<option value='-1'>-- Seleccione la Localidad/ciudad --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#sol_con_dir_localidad_ciudad").append("<option value='"+id+"'>"+name+"</option>");
                }
                
                $("#sol_con_cod_barrio").empty();
                $("#sol_con_cod_barrio").append("<option value='-1'>-- Antes seleccione la Localidad/ciudad --</option>");
            }
        });
    }
    
    $("#sol_con_dir_provincia").change(function(){
        sol_con_dir_provincia();
    });
    
    function sol_con_dir_localidad_ciudad()
    {
        var parent_codigo = $("#sol_con_dir_localidad_ciudad").val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_barrio_zona_uv', parent_tipo:'dir_localidad_ciudad'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#sol_con_cod_barrio").empty();
                $("#sol_con_cod_barrio").append("<option value='-1'>-- Seleccione el Barrio/zona/uv --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#sol_con_cod_barrio").append("<option value='"+id+"'>"+name+"</option>");
                }
            }
        });
    }
    
    $("#sol_con_dir_localidad_ciudad").change(function(){
        sol_con_dir_localidad_ciudad();
    });

    // Dirección Domicilio
    
    function sol_con_dir_departamento_dom()
    {
        var parent_codigo = $("#sol_con_dir_departamento_dom").val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_provincia', parent_tipo:'dir_departamento'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#sol_con_dir_provincia_dom").empty();
                $("#sol_con_dir_provincia_dom").append("<option value='-1'>-- Seleccione la Provincia --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#sol_con_dir_provincia_dom").append("<option value='"+id+"'>"+name+"</option>");
                }
                $("#sol_con_dir_localidad_ciudad_dom").empty();
                $("#sol_con_dir_localidad_ciudad_dom").append("<option value='-1'>-- Antes seleccione la Provincia --</option>");
                
                $("#sol_con_cod_barrio_dom").empty();
                $("#sol_con_cod_barrio_dom").append("<option value='-1'>-- Antes seleccione la Localidad/ciudad --</option>");
            }
        });
    }
    
    $("#sol_con_dir_departamento_dom").change(function(){
        sol_con_dir_departamento_dom();
    });
    
    function sol_con_dir_provincia_dom()
    {
        var parent_codigo = $("#sol_con_dir_provincia_dom").val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_localidad_ciudad', parent_tipo:'dir_provincia'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#sol_con_dir_localidad_ciudad_dom").empty();
                $("#sol_con_dir_localidad_ciudad_dom").append("<option value='-1'>-- Seleccione la Localidad/ciudad --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#sol_con_dir_localidad_ciudad_dom").append("<option value='"+id+"'>"+name+"</option>");
                }
                
                $("#sol_con_cod_barrio_dom").empty();
                $("#sol_con_cod_barrio_dom").append("<option value='-1'>-- Antes seleccione la Localidad/ciudad --</option>");
            }
        });
    }
    
    $("#sol_con_dir_provincia_dom").change(function(){
        sol_con_dir_provincia_dom();
    });
    
    function sol_con_dir_localidad_ciudad_dom()
    {
        var parent_codigo = $("#sol_con_dir_localidad_ciudad_dom").val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_barrio_zona_uv', parent_tipo:'dir_localidad_ciudad'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#sol_con_cod_barrio_dom").empty();
                $("#sol_con_cod_barrio_dom").append("<option value='-1'>-- Seleccione el Barrio/zona/uv --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#sol_con_cod_barrio_dom").append("<option value='"+id+"'>"+name+"</option>");
                }
            }
        });
    }
    
    $("#sol_con_dir_localidad_ciudad_dom").change(function(){
        sol_con_dir_localidad_ciudad_dom();
    });

    // ### DIRECCIÓN CÓNYUGE  FIN
    
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

    <?php echo $this->mfunciones_generales->ContenidoModalConfirma(); ?>
    
    <div id="estructura_principal" class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <?php // ESTRUCTURA PRINCIPAL ?>
        
        <br />
        <div class='col-sm-12' style="text-align: center;">
            <div class='form-group'>
                <span class="nav-avanza" style="padding: 5px 10px;" onclick="ShowcopyDirSol();">
                    ¿Copiar direcciones del Solicitante?
                </span>
            </div>
        </div>
        <div style="clear: both"></div>
        
        <div class="panel panel-default informacion_general">
            <div class="panel-heading">CÓNYUGE - DIRECCIÓN DEL NEGOCIO/ACTIVIDAD O LUGAR DE TRABAJO (opcional)</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_dir_departamento'><?php echo $this->lang->line('sol_con_dir_departamento'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_con_dir_departamento', $arrRespuesta[0]['sol_con_dir_departamento'], 'dir_departamento'); ?></div></div>
                <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='sol_con_dir_provincia'><?php echo $this->lang->line('sol_con_dir_provincia'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_con_dir_provincia', $arrRespuesta[0]['sol_con_dir_provincia'], 'dir_provincia', $arrRespuesta[0]['sol_con_dir_departamento'], 'dir_departamento'); ?></div></div>
                <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='sol_con_dir_localidad_ciudad'><?php echo $this->lang->line('sol_con_dir_localidad_ciudad'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_con_dir_localidad_ciudad', $arrRespuesta[0]['sol_con_dir_localidad_ciudad'], 'dir_localidad_ciudad', $arrRespuesta[0]['sol_con_dir_provincia'], 'dir_provincia'); ?></div></div>
                <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='sol_con_cod_barrio'><?php echo $this->lang->line('sol_con_cod_barrio'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_con_cod_barrio', $arrRespuesta[0]['sol_con_cod_barrio'], 'dir_barrio_zona_uv', $arrRespuesta[0]['sol_con_dir_localidad_ciudad'], 'dir_localidad_ciudad'); ?></div></div>
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_direccion_trabajo'><?php echo $this->lang->line('sol_con_direccion_trabajo'); ?>:</label><?php echo $arrCajasHTML['sol_con_direccion_trabajo']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_edificio_trabajo'><?php echo $this->lang->line('sol_con_edificio_trabajo'); ?>:</label><?php echo $arrCajasHTML['sol_con_edificio_trabajo']; ?></div></div>
                <div class='col-sm-4'><div class='form-group'><label class='label-campo' for='sol_con_numero_trabajo'><?php echo $this->lang->line('sol_con_numero_trabajo'); ?>:</label><?php echo $arrCajasHTML['sol_con_numero_trabajo']; ?></div></div>
                
                <div class='col-sm-4'><div class='form-group'><label class='label-campo' for='sol_con_trabajo_lugar'><?php echo $this->lang->line('sol_con_trabajo_lugar'); ?>:</label><br /><?php echo $arrCajasHTML['sol_con_trabajo_lugar']; ?><?php echo $arrCajasHTML['sol_con_trabajo_lugar_otro']; ?></div></div>
                <div class='col-sm-4'><div class='form-group'><label class='label-campo' for='sol_con_trabajo_realiza'><?php echo $this->lang->line('sol_con_trabajo_realiza'); ?>:</label><?php echo $arrCajasHTML['sol_con_trabajo_realiza']; ?><?php echo $arrCajasHTML['sol_con_trabajo_realiza_otro']; ?></div></div>
                
            </div>
        </div>
        
        <div class='col-sm-12' style="text-align: center;"><div class='form-group'><label class='label-campo' for='sol_con_dir_referencia'>Cónyuge - Negocio/Actividad - <?php echo $this->lang->line('sol_con_dir_referencia'); ?>:</label><br /><?php echo $arrCajasHTML['sol_con_dir_referencia']; ?></div></div>
        <div style="clear: both"></div>
        <br />
        
        <div class="panel panel-default informacion_general sol_con_dir_referencia_geo">
            <div class="panel-heading">Cónyuge - Dirección del Negocio/Actividad Geolocalización</div>
            <div class="panel-body">
                
                <?php $url_armado = $this->mfunciones_generales->setEnlaceRegistro($estructura_id, 0, 'sol_con_geolocalizacion'); ?>
                <iframe frameborder="0" scrolling="no" style="overflow-y: hidden; width: 100%; background-color: #ffffff; height: 350px;" src="<?php echo (site_url('Registros/SolMapa/?' . $url_armado));?>"> </iframe>
                
            </div>
        </div>
    
        <div class="panel panel-default informacion_general sol_con_dir_referencia_croq">
            <div class="panel-heading">Cónyuge - Dirección del Negocio/Actividad Croquis</div>
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
                            if ($this->mfunciones_microcreditos->validateIMG_simple($arrRespuesta[0]['sol_con_croquis']) != '') {
                                echo '<img id="img_croquis__1" src="' . $arrRespuesta[0]['sol_con_croquis'] . '" class="responsive-img " style="display:block;">';
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
        
        <div class="panel panel-default informacion_general">
            <div class="panel-heading"> <i class="fa fa-home" aria-hidden="true"></i> CÓNYUGE - DIRECCIÓN DEL DOMICILIO (opcional)</div>
            <div class="panel-body">
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_dir_departamento_dom'><?php echo $this->lang->line('sol_con_dir_departamento_dom'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_con_dir_departamento_dom', $arrRespuesta[0]['sol_con_dir_departamento_dom'], 'dir_departamento'); ?></div></div>
                <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='sol_con_dir_provincia_dom'><?php echo $this->lang->line('sol_con_dir_provincia_dom'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_con_dir_provincia_dom', $arrRespuesta[0]['sol_con_dir_provincia_dom'], 'dir_provincia', $arrRespuesta[0]['sol_con_dir_departamento_dom'], 'dir_departamento'); ?></div></div>
                <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='sol_con_dir_localidad_ciudad_dom'><?php echo $this->lang->line('sol_con_dir_localidad_ciudad_dom'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_con_dir_localidad_ciudad_dom', $arrRespuesta[0]['sol_con_dir_localidad_ciudad_dom'], 'dir_localidad_ciudad', $arrRespuesta[0]['sol_con_dir_provincia_dom'], 'dir_provincia'); ?></div></div>
                <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='sol_con_cod_barrio_dom'><?php echo $this->lang->line('sol_con_cod_barrio_dom'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('sol_con_cod_barrio_dom', $arrRespuesta[0]['sol_con_cod_barrio_dom'], 'dir_barrio_zona_uv', $arrRespuesta[0]['sol_con_dir_localidad_ciudad_dom'], 'dir_localidad_ciudad'); ?></div></div>
                
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_direccion_dom'><?php echo $this->lang->line('sol_con_direccion_dom'); ?>:</label><?php echo $arrCajasHTML['sol_con_direccion_dom']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_edificio_dom'><?php echo $this->lang->line('sol_con_edificio_dom'); ?>:</label><?php echo $arrCajasHTML['sol_con_edificio_dom']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='sol_con_numero_dom'><?php echo $this->lang->line('sol_con_numero_dom'); ?>:</label><?php echo $arrCajasHTML['sol_con_numero_dom']; ?></div></div>
                <div class='col-sm-7'><div class='form-group'><label class='label-campo' for='sol_con_dom_tipo'><?php echo $this->lang->line('sol_con_dom_tipo'); ?>:</label><br /><?php echo $arrCajasHTML['sol_con_dom_tipo']; ?><?php echo $arrCajasHTML['sol_con_dom_tipo_otro']; ?></div></div>

                <div style="clear: both"></div>
                
            </div>
        </div>
                
        <div class='col-sm-12' style="text-align: center;"><div class='form-group'><label class='label-campo' for='sol_con_dir_referencia_dom'>Cónyuge - Domicilio - <?php echo $this->lang->line('sol_con_dir_referencia_dom'); ?>:</label><br /><?php echo $arrCajasHTML['sol_con_dir_referencia_dom']; ?></div></div>
        <div style="clear: both"></div>
        <br />
        
        <div class="panel panel-default informacion_general sol_con_dir_referencia_dom_geo">
            <div class="panel-heading">Cónyuge - Dirección del Domicilio Geolocalización</div>
            <div class="panel-body">

                <div class='col-sm-12' style="text-align: center;">

                    <div class='form-group'>

                        <?php $url_armado = $this->mfunciones_generales->setEnlaceRegistro($estructura_id, 0, 'sol_con_geolocalizacion_dom'); ?>
                        <iframe frameborder="0" scrolling="no" style="overflow-y: hidden; width: 100%; background-color: #ffffff; height: 350px;" src="<?php echo (site_url('Registros/SolMapa/?' . $url_armado));?>"> </iframe>

                    </div>

                </div>

            </div>
        </div>

        <div class="panel panel-default informacion_general sol_con_dir_referencia_dom_croq">
            <div class="panel-heading">Cónyuge - Dirección del Domicilio Croquis</div>
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
                            if ($this->mfunciones_microcreditos->validateIMG_simple($arrRespuesta[0]['sol_con_croquis_dom']) != '') {
                                echo '<img id="img_croquis__2" src="' . $arrRespuesta[0]['sol_con_croquis_dom'] . '" class="responsive-img " style="display:block;">';
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

<div class="modal fade" id="pregunta_CopyDir" role="dialog">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="pregunta_titulo">Copiar las direcciones del solicitante al cónyuge sobrescribirá los datos que no hayan sido guardados en esta pantalla, una vez copiados podrá editar y/o guardar la información ¿Continuar?</h4>
        </div>
        <div class="modal-body">
            <div style="text-align: center;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 130px !important;">Cancelar</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 8px 0px !important; width: 150px !important;" onclick="copyDirSol()">Si, Continuar</button>
            </div>
        </div>
    </div>
</div>
</div>