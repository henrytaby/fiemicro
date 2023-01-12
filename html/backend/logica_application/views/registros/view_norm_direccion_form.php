<script type="text/javascript">
    
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDireccion", "FormularioDireccion");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioDireccion", '../NormDir/Guardar',
            'direccion_formulario', 'divErrorDireccion');
    
    
<?php
    // ESTRUCTURA PRINCIPAL
    $resources_croquis = $this->mfunciones_microcreditos->resCroquis(1);

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
    
    $(document).ready(function() {
        
        $("div.modal-backdrop").remove();
        
        $('.contenido_formulario').find("input[type=text]").each(function(ev)
        {
            $(this).attr("placeholder", " :: Registrar ::");
        });
        $('.contenido_formulario').find("input[type=tel]").each(function(ev)
        {
            $(this).attr("placeholder", " :: Registrar ::");
        });
        
        $("#rd_referencia, #rd_tipo").togglebutton();
        $("div.modal-backdrop").remove();

        if(parseInt($("#rd_dir_departamento").val()) != -1)
        {
            if(parseInt($("#rd_cod_barrio").val()) <= 0){ rd_dir_localidad_ciudad(); }
            if(parseInt($("#rd_dir_localidad_ciudad").val()) <= 0){ rd_dir_provincia(); }
            if(parseInt($("#rd_dir_provincia").val()) <= 0){ rd_dir_departamento(); }
        }

        if(parseInt($("#rd_dir_departamento_dom").val()) != -1)
        {
            if(parseInt($("#rd_cod_barrio_dom").val()) <= 0){ rd_dir_localidad_ciudad_dom(); }
            if(parseInt($("#rd_dir_localidad_ciudad_dom").val()) <= 0){ rd_dir_provincia_dom(); }
            if(parseInt($("#rd_dir_provincia_dom").val()) <= 0){ rd_dir_departamento_dom(); }
        }
        
        rd_tipo();
        rd_referencia();
        rd_trabajo_lugar();
        rd_trabajo_realiza();
        rd_dom_tipo();
        
        <?php
        // Actividad Secundaria
        if($act_secundaria == 1)
        {
            echo ' rd_referencia_sec(); rd_trabajo_lugar_sec(); rd_trabajo_realiza_sec();';
        }
        ?>
    });

    // ### REFERENCIA DIRECCIÓN SOLICITANTE  INICIO

    // Dirección Negocio
    function rd_referencia()
    {
        $(".rd_referencia_geo, .rd_referencia_croq").hide();
            
        switch (parseInt($("#rd_referencia").val())) {
            case 1:
                $(".rd_referencia_geo").fadeIn();
                break;
            case 2:
                $(".rd_referencia_croq").fadeIn();
                break;
            default:
                break;
        }
    }

    $("#rd_referencia").on('change', function(){
        rd_referencia();
    });

    // ### REFERENCIA DIRECCIÓN SOLICITANTE  FIN
    
    // ### MAPAS  INICIO
    
    // Solicitante GEO Negocio
    <?php $campo_geo = 'rd_croquis'; ?>
    $('<input>').attr({
        type: 'hidden',
        id: 'croquis_base64__1',
        name: 'croquis_base64__1',
        value: '<?php echo $arrRespuesta[0][$campo_geo]; ?>'
    }).appendTo('#FormularioDireccion');
    
    <?php $campo_geo = 'rd_geolocalizacion'; ?>
    $('<input>').attr({
        type: 'hidden',
        id: '<?php echo $campo_geo; ?>',
        name: '<?php echo $campo_geo; ?>',
        value: '<?php echo $arrRespuesta[0][$campo_geo]; ?>'
    }).appendTo('#FormularioDireccion');
    
    function actualizar_<?php echo $campo_geo; ?>(id) {
        document.getElementById('<?php echo $campo_geo; ?>').value = id;
    }
    
    // ### MAPAS  FIN
    
    // ### VALORES "OTRO" - SOLICITANTE  INICIO
    
    // rd_trabajo_lugar
    function rd_trabajo_lugar()
    {
        $("#rd_trabajo_lugar_otro").fadeOut();
            
        if(parseInt($("#rd_trabajo_lugar").val()) == 99)
        {
            $("#rd_trabajo_lugar_otro").fadeIn();
        }
    }

    $("#rd_trabajo_lugar").on('change', function(){
        rd_trabajo_lugar();
    });
    
    // rd_trabajo_realiza
    function rd_trabajo_realiza()
    {
        $("#rd_trabajo_realiza_otro").fadeOut();
            
        if(parseInt($("#rd_trabajo_realiza").val()) == 99)
        {
            $("#rd_trabajo_realiza_otro").fadeIn();
        }
    }

    $("#rd_trabajo_realiza").on('change', function(){
        rd_trabajo_realiza();
    });
    
    // rd_dom_tipo
    function rd_dom_tipo()
    {
        $("#rd_dom_tipo_otro").fadeOut();
            
        if(parseInt($("#rd_dom_tipo").val()) == 99)
        {
            $("#rd_dom_tipo_otro").fadeIn();
        }
    }

    $("#rd_dom_tipo").on('change', function(){
        rd_dom_tipo();
    });
    
    // ### VALORES "OTRO" - SOLICITANTE  FIN
    
    // ### DIRECCIÓN SOLICITANTE  INICIO
    
    // Dirección Negocio
    
    function rd_dir_departamento()
    {
        var parent_codigo = $("#rd_dir_departamento").val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_provincia', parent_tipo:'dir_departamento'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#rd_dir_provincia").empty();
                $("#rd_dir_provincia").append("<option value='-1'>-- Seleccione la Provincia --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#rd_dir_provincia").append("<option value='"+id+"'>"+name+"</option>");
                }
                $("#rd_dir_localidad_ciudad").empty();
                $("#rd_dir_localidad_ciudad").append("<option value='-1'>-- Antes seleccione la Provincia --</option>");
                
                $("#rd_cod_barrio").empty();
                $("#rd_cod_barrio").append("<option value='-1'>-- Antes seleccione la Localidad/ciudad --</option>");
            }
        });
    }
    
    $("#rd_dir_departamento").change(function(){
        rd_dir_departamento();
    });
    
    function rd_dir_provincia()
    {
        var parent_codigo = $("#rd_dir_provincia").val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_localidad_ciudad', parent_tipo:'dir_provincia'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#rd_dir_localidad_ciudad").empty();
                $("#rd_dir_localidad_ciudad").append("<option value='-1'>-- Seleccione la Localidad/ciudad --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#rd_dir_localidad_ciudad").append("<option value='"+id+"'>"+name+"</option>");
                }
                
                $("#rd_cod_barrio").empty();
                $("#rd_cod_barrio").append("<option value='-1'>-- Antes seleccione la Localidad/ciudad --</option>");
            }
        });
    }
    
    $("#rd_dir_provincia").change(function(){
        rd_dir_provincia();
    });
    
    function rd_dir_localidad_ciudad()
    {
        var parent_codigo = $("#rd_dir_localidad_ciudad").val();
        $.ajax({
            url: '../../Afiliador/Select/Cargar',
            type: 'post',
            data: {
                parent_codigo:parent_codigo, tipo:'dir_barrio_zona_uv', parent_tipo:'dir_localidad_ciudad'
            },
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#rd_cod_barrio").empty();
                $("#rd_cod_barrio").append("<option value='-1'>-- Seleccione el Barrio/zona/uv --</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['lista_codigo'];
                    var name = response[i]['lista_valor'];
                    $("#rd_cod_barrio").append("<option value='"+id+"'>"+name+"</option>");
                }
            }
        });
    }
    
    $("#rd_dir_localidad_ciudad").change(function(){
        rd_dir_localidad_ciudad();
    });
    
    // ### DIRECCIÓN SOLICITANTE  FIN

    function pregunta_dir_acciones()
    {
        $("#divOpciones").show();
        $("#confirmacion").hide();
        $("#pregunta_dir_acciones").modal();
    }
    
    // Dirección Negocio
    function rd_tipo()
    {
        $(".direccion_trabajo, .direccion_domicilio").hide();
            
        switch (parseInt($("#rd_tipo").val())) {
            case 1:
                $(".direccion_trabajo").fadeIn();
                break;
            case 2:
                $(".direccion_domicilio").fadeIn();
                break;
            default:
                break;
        }
    }

    $("#rd_tipo").on('change', function(){
        rd_tipo();
    });
    
    $("#divOpciones").show();
    $("#confirmacion").hide();

    function MostrarConfirmacion()
    {
        $("#divOpciones").hide();
        $("#confirmacion").fadeIn(500);
    }
    
    function OcultarConfirmacion()
    {
        $("#divOpciones").fadeIn(500);
        $("#confirmacion").hide();
    }

    function EliminarDireccion(dir_codigo, registro_tipo, registro_codigo) {
        
        var strParametros = "&dir_codigo=" + dir_codigo + "&registro_tipo=" + registro_tipo + "&registro_codigo=" + registro_codigo;
        Ajax_CargadoGeneralPagina('../NormDir/Eliminar', 'direccion_formulario', "divErrorListaResultado", 'SIN_FOCUS', strParametros);
    }

</script>

    <!-- CSS CORQUIS ––––––––––––––––––––––––––––––––––––––––––––––––––--->
    <style>
        <?php echo $resources_croquis->croquis_css; ?>
    </style>

    <div id="divErrorDireccion" class="mensajeBD"> </div>

    <form id="FormularioDireccion" method="post">
    
        <div class='col-sm-12' style="text-align: center;"><div class='form-group'><label class='label-campo' for='rd_referencia'><?php echo mb_strtoupper($this->lang->line('rd_tipo')); ?>:</label><br /><?php echo $arrCajasHTML['rd_tipo']; ?></div></div>
        <div style="clear: both"></div>

        <div class="panel panel-default informacion_general">
            <div class="panel-heading">DIRECCIÓN</div>
            <div class="panel-body">

                <input type="hidden" name="dir_contador" value="<?php if(isset($dir_contador)){ echo $dir_contador; } ?>" />
                <input type="hidden" name="dir_codigo" value="<?php if(isset($dir_codigo)){ echo $dir_codigo; } ?>" />
                <input type="hidden" name="registro_tipo" value="<?php if(isset($registro_tipo)){ echo $registro_tipo; } ?>" />
                <input type="hidden" name="registro_codigo" value="<?php if(isset($registro_codigo)){ echo $registro_codigo; } ?>" />

                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='rd_dir_departamento'><?php echo $this->lang->line('rd_dir_departamento'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('rd_dir_departamento', $arrRespuesta[0]['rd_dir_departamento'], 'dir_departamento'); ?></div></div>
                <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='rd_dir_provincia'><?php echo $this->lang->line('rd_dir_provincia'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('rd_dir_provincia', $arrRespuesta[0]['rd_dir_provincia'], 'dir_provincia', $arrRespuesta[0]['rd_dir_departamento'], 'dir_departamento'); ?></div></div>
                <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='rd_dir_localidad_ciudad'><?php echo $this->lang->line('rd_dir_localidad_ciudad'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('rd_dir_localidad_ciudad', $arrRespuesta[0]['rd_dir_localidad_ciudad'], 'dir_localidad_ciudad', $arrRespuesta[0]['rd_dir_provincia'], 'dir_provincia'); ?></div></div>
                <div class='col-sm-4-aux depende'><div class='form-group'><label class='label-campo' for='rd_cod_barrio'><?php echo $this->lang->line('rd_cod_barrio'); ?>:</label><?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('rd_cod_barrio', $arrRespuesta[0]['rd_cod_barrio'], 'dir_barrio_zona_uv', $arrRespuesta[0]['rd_dir_localidad_ciudad'], 'dir_localidad_ciudad'); ?></div></div>

                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='rd_direccion'><?php echo $this->lang->line('rd_direccion'); ?>:</label><?php echo $arrCajasHTML['rd_direccion']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='rd_edificio'><?php echo $this->lang->line('rd_edificio'); ?>:</label><?php echo $arrCajasHTML['rd_edificio']; ?></div></div>
                <div class='col-sm-4-aux'><div class='form-group'><label class='label-campo' for='rd_numero'><?php echo $this->lang->line('rd_numero'); ?>:</label><?php echo $arrCajasHTML['rd_numero']; ?></div></div>

                <div class='col-sm-4 direccion_trabajo'><div class='form-group'><label class='label-campo' for='rd_trabajo_lugar'><?php echo $this->lang->line('rd_trabajo_lugar'); ?>:</label><br /><?php echo $arrCajasHTML['rd_trabajo_lugar']; ?><?php echo $arrCajasHTML['rd_trabajo_lugar_otro']; ?></div></div>
                <div class='col-sm-4 direccion_trabajo'><div class='form-group'><label class='label-campo' for='rd_trabajo_realiza'><?php echo $this->lang->line('rd_trabajo_realiza'); ?>:</label><?php echo $arrCajasHTML['rd_trabajo_realiza']; ?><?php echo $arrCajasHTML['rd_trabajo_realiza_otro']; ?></div></div>
                <div class='col-sm-4 direccion_domicilio'><div class='form-group'><label class='label-campo' for='rd_dom_tipo'><?php echo $this->lang->line('rd_dom_tipo'); ?>:</label><br /><?php echo $arrCajasHTML['rd_dom_tipo']; ?><?php echo $arrCajasHTML['rd_dom_tipo_otro']; ?></div></div>
                <div class='col-sm-4'><div class='form-group'><label class='label-campo' for='rd_referencia_texto'><?php echo $this->lang->line('rd_referencia_texto'); ?>:</label><?php echo $arrCajasHTML['rd_referencia_texto']; ?></div></div>

            </div>
        </div>

        <div class='col-sm-12' style="text-align: center;"><div class='form-group'><label class='label-campo' for='rd_referencia'><?php echo $this->lang->line('rd_referencia'); ?>:</label><br /><?php echo $arrCajasHTML['rd_referencia']; ?></div></div>
        <div style="clear: both"></div>
        <br />

        <div class="panel panel-default informacion_general rd_referencia_geo">
            <div class="panel-heading">Dirección - Geolocalización</div>
            <div class="panel-body">

                <?php $url_armado = $this->mfunciones_generales->setEnlaceRegistro($estructura_id, 0, $arrRespuesta[0]['rd_geolocalizacion']); ?>
                <iframe frameborder="0" scrolling="no" style="overflow-y: hidden; width: 100%; background-color: #ffffff; height: 350px;" src="<?php echo (site_url('Registros/NormMapa/?' . $url_armado));?>"> </iframe>

            </div>
        </div>

        <div class="panel panel-default informacion_general rd_referencia_croq">
            <div class="panel-heading">Dirección - Croquis</div>

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
                            if ($this->mfunciones_microcreditos->validateIMG_simple($arrRespuesta[0]['rd_croquis']) != '') {
                                echo '<img id="img_croquis__1" src="' . $arrRespuesta[0]['rd_croquis'] . '" class="responsive-img " style="display:block;">';
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

        <div class="modal fade" id="pregunta_dir_acciones" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="pregunta_titulo">Seleccione la acción a realizar<br />DIRECCIÓN <?php echo ($dir_codigo!=-1 ? '#' . $dir_contador : ' NUEVA'); ?></h4>
                </div>
                <div class="modal-body">
                    <div style="text-align: center;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 130px !important;">Cancelar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 8px 0px !important; width: 150px !important;" id="btnGuardarDireccion">Guardar</button>
                        
                        <?php
                    
                        if($dir_codigo != -1)
                        {
                            echo '
                                <br /><br />
                                <button type="button" class="btn btn-danger" style="border-radius: 0px !important; padding: 8px 0px !important; width: 150px !important;" onclick="MostrarConfirmacion();">Eliminar</button>

                                ';
                        }

                        ?>
                        
                        <div id="confirmacion" style="text-align: center;">
            
                            <br />
                            
                            <span class="texto-borrar">¿Confirma la acción de Eliminar?</span><br /><br />

                            <div class="container" style="margin-top: 5px;">
                                <div class="row">

                                    <div class="col" style="text-align: center;">
                                        <span class="nav-borrar" id="" onclick="OcultarConfirmacion();">Cancelar </span>
                                    </div>

                                    <div class="col" style="text-align: center;">
                                        <span class="nav-borrar" data-dismiss="modal" onclick="EliminarDireccion('<?php echo $dir_codigo; ?>', '<?php echo $registro_tipo; ?>', '<?php echo $registro_codigo; ?>');"> ELIMINAR </span>
                                    </div>

                                </div>

                            </div>

                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        </div>
        
    </form>