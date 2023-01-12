<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Conf/General/Guardar',
            'divVistaMenuPantalla', 'divErrorListaResultado');

    $("#divCargarFormulario").show();    
    $("#confirmacion").hide();

    function MostrarConfirmación()
    {
        if(!check_json_params('conf_f_cobis_uri_cliente_alta_params') || !check_json_params('conf_f_cobis_uri_apertura_params'))
        {
            return;
        }
        
        $("#divCargarFormulario").hide();
        $("#confirmacion").fadeIn(500);
    }
    
    function OcultarConfirmación()
    {
        $("#divCargarFormulario").fadeIn(500);    
        $("#confirmacion").hide();
    }

    var startTimeTextBox1 = $('#conf_atencion_desde1');
    var endTimeTextBox1 = $('#conf_atencion_hasta1');

    $.timepicker.timeRange(
        startTimeTextBox1,
        endTimeTextBox1,
        {
            minInterval: (1000*60*60), // 1hr
            timeFormat: 'HH:mm',
            start: {}, // start picker options
            end: {} // end picker options
        }
    );
    
    var startTimeTextBox3 = $('#conf_atencion_hasta1');
    var endTimeTextBox3 = $('#conf_atencion_desde2');

    $.timepicker.timeRange(
        startTimeTextBox3,
        endTimeTextBox3,
        {
            minInterval: (1000*60*60), // 1hr
            timeFormat: 'HH:mm',
            start: {}, // start picker options
            end: {} // end picker options
        }
    );
    
    var startTimeTextBox2 = $('#conf_atencion_desde2');
    var endTimeTextBox2 = $('#conf_atencion_hasta2');

    $.timepicker.timeRange(
        startTimeTextBox2,
        endTimeTextBox2,
        {
            minInterval: (1000*60*60), // 1hr
            timeFormat: 'HH:mm',
            start: {}, // start picker options
            end: {} // end picker options
        }
    );
    
    $(document).ready(function(){ 
        check_json_params('conf_f_cobis_uri_cliente_alta_params');
        check_json_params('conf_f_cobis_uri_apertura_params');
    });
    
    function IsValidJSONString(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }
    
    function check_json_params(id)
    {
        myJsObj = $("#"+id).val();
        
        if(!IsValidJSONString(myJsObj))
        {
            document.getElementById(id+'_txt').innerHTML = '<i class="fa fa-times" aria-hidden="true"></i> Error de sintaxis';
            
            var txt_alert = "Parámetros por defecto";
            
            switch (id) 
            {
                case 'conf_f_cobis_uri_cliente_alta_params':

                    txt_alert = "<?php echo $this->lang->line('conf_f_cobis_uri_cliente_alta_params'); ?>";

                    break;
                    
                case 'conf_f_cobis_uri_apertura_params':

                    txt_alert = "<?php echo $this->lang->line('conf_f_cobis_uri_apertura_params'); ?>";

                    break;

                default:

                    break;
            }
            
            alert(txt_alert + ' deben estar en formato JSON válido. Revise la sintaxis.');
            return false;
        }

        // using JSON.stringify pretty print capability:
        var str = JSON.stringify(JSON.parse(myJsObj), undefined, 4);
        // display pretty printed object in text area:
        document.getElementById(id).value = str;

        document.getElementById(id+'_txt').innerHTML = '<i class="fa fa-check-square-o" aria-hidden="true"></i> Sintaxis correcta';
        
        return true;
    }
    
    $("#conf_rekognition_secret").attr("type", "password");
    
    function Ajax_CargarAccion_Test_WS(end_point) {
        
        if(!check_json_params('conf_f_cobis_uri_cliente_alta_params') || !check_json_params('conf_f_cobis_uri_apertura_params'))
        {
            return;
        }
        
        var strParametros = 
            "&end_point=" + $("#"+end_point).val() + 
            "&codigo_ws=" + end_point + 
            "&conf_jwt_client_secret=" + $("#conf_jwt_client_secret").val() + 
            "&conf_jwt_username=" + $("#conf_jwt_username").val() + 
            "&conf_jwt_password=" + $("#conf_jwt_password").val() + 
            "&conf_f_cobis_header=" + $("#conf_f_cobis_header").val() + 
            "&conf_f_cobis_uri_cliente_alta_params=" + $("#conf_f_cobis_uri_cliente_alta_params").val() + 
            "&conf_f_cobis_uri_apertura_params=" + $("#conf_f_cobis_uri_apertura_params").val() + 
            "&conf_ad_activo=" + $("#conf_ad_activo").val() + 
            "&conf_ad_host=" + $("#conf_ad_host").val() + 
            "&conf_ad_dominio=" + $("#conf_ad_dominio").val() + 
            "&conf_ad_dn=" + $("#conf_ad_dn").val() + 
            "&conf_ad_test_user=" + $("#conf_ad_test_user").val() + 
            "&conf_ad_test_pass=" + $("#conf_ad_test_pass").val() + 
            "&conf_sms_name_plantilla=" + $("#conf_sms_name_plantilla").val() + 
            "&conf_sms_channelid=" + $("#conf_sms_channelid").val() + 
            "&sms_cel_test=" + $("#sms_cel_test").val();
    
        Ajax_CargadoGeneralPagina('Conf/General/TestWS', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    
    $("#conf_jwt_client_secret").attr("type", "password");
    $("#conf_jwt_username").attr("type", "password");
    $("#conf_jwt_password").attr("type", "password");
    
    function MostrarOcultarJWT()
    {
        if ($("#conf_jwt_client_secret").attr("type") == "password") {
            $("#conf_jwt_client_secret").attr("type", "text");
            $("#conf_jwt_username").attr("type", "text");
            $("#conf_jwt_password").attr("type", "text");
        } else {
            $("#conf_jwt_client_secret").attr("type", "password");
            $("#conf_jwt_username").attr("type", "password");
            $("#conf_jwt_password").attr("type", "password");
        }
    }
    
    $("#conf_credito_notificar_sms_bearer").attr("type", "password");
    
    $("#conf_f_cobis_header").attr("type", "password");
    function MostrarOcultarCobisHeader()
    {
        if ($("#conf_f_cobis_header").attr("type") == "password") {
            $("#conf_f_cobis_header").attr("type", "text");
        } else {
            $("#conf_f_cobis_header").attr("type", "password");
        }
    }
    
    $("#conf_ad_test_pass").attr("type", "password");
    function MostrarOcultarADpass()
    {
        if ($("#conf_ad_test_pass").attr("type") == "password") {
            $("#conf_ad_test_pass").attr("type", "text");
        } else {
            $("#conf_ad_test_pass").attr("type", "password");
        }
    }
    
    function VerTablaConf(id) {

        $("#"+id).slideToggle();
    }
    
    $("#conf_sms_permitido_cantidad").on('change', function(){
        conf_sms_permitido_cantidad_sel();
    });
    
    function conf_sms_permitido_cantidad_sel()
    {
        var valor = parseInt($('#conf_sms_permitido_cantidad option:selected').val());
        
        if(valor == 0)
        {
            $('#td_conf_sms_permitido_tiempo').hide();
        }
        else
        {
            $('#td_conf_sms_permitido_tiempo').show();
        }
    }
    conf_sms_permitido_cantidad_sel();
    
</script>

<style>

    .titulo_seccion_conf
    {
        color: #006699;
        font-size: 16px;
        text-shadow: #004162 0px 1px 1px;
        margin-bottom: 5px;
        margin-left: 20px;
        text-align: left;
    }

</style>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('conf_general_Titulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('conf_general_Subtitulo'); ?></div>
                
        <div style="clear: both"></div>
        
        <br />

        <form id="FormularioRegistroLista" method="post">

                                <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="Campana/Ver" />

            <input type="hidden" name="conf_general_id" value="<?php if(isset($arrRespuesta[0]["conf_general_id"])){ echo $arrRespuesta[0]["conf_general_id"]; } ?>" />

        <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaConf('general');">
            <div class="titulo_seccion_conf"> <i class="fa fa-eye" aria-hidden="true"></i> CONFIGURACIÓN GENERAL </div>
        </div>
        
        <div id="general" style="display: none;">

            <br />
            
            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php $strClase = "FilaBlanca"; ?>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_general_key_google'); ?>

                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="La Key de Google tiene que estar habilitada para Mapas y Calendario" data-balloon-pos="right"> </span>

                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_general_key_google"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_horario_feriado'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Al habilitar esta opción, se mostrarán los días festivos en el calendario" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">                
                        <input id="feriado1" name="conf_horario_feriado" type="radio" class="" <?php if($arrRespuesta[0]["conf_horario_feriado"]==0) echo "checked='checked'"; ?> value="0" />
                        <label for="feriado1" class=""><span></span><?php echo $this->lang->line('Catalogo_no'); ?></label>

                        &nbsp;&nbsp;

                        <input id="feriado2" name="conf_horario_feriado" type="radio" class="" <?php if($arrRespuesta[0]["conf_horario_feriado"]==1) echo "checked='checked'"; ?> value="1" />
                        <label for="feriado2" class=""><span></span><?php echo $this->lang->line('Catalogo_si'); ?></label>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_horario_laboral'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Si esta opción no esta habilitada, el calendario aceptará horarios de todo el día" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">                
                        <input id="activo1" name="conf_horario_laboral" type="radio" class="" <?php if($arrRespuesta[0]["conf_horario_laboral"]==0) echo "checked='checked'"; ?> value="0" />
                        <label for="activo1" class=""><span></span><?php echo $this->lang->line('Catalogo_no'); ?></label>

                        &nbsp;&nbsp;

                        <input id="activo2" name="conf_horario_laboral" type="radio" class="" <?php if($arrRespuesta[0]["conf_horario_laboral"]==1) echo "checked='checked'"; ?> value="1" />
                        <label for="activo2" class=""><span></span><?php echo $this->lang->line('Catalogo_si'); ?></label>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        Turno Mañana
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Si requiere establecer sólo un turno, puede igualar el segundo periodo, por ejemplo: 08:30 a 12:30 y 12:30 a 18:30 " data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        
                        <table style="width: 100%;" border="0">
                            <tr>
                                <td style="width: 50%;">
                                    Desde<br /><?php echo $arrCajasHTML["conf_atencion_desde1"]; ?>
                                </td>
                                <td style="width: 50%;">
                                    Hasta<br /><?php echo $arrCajasHTML["conf_atencion_hasta1"]; ?>
                                </td>
                            </tr>
                        </table>
                        
                    </td>

                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        Turno Tarde
                    </td>

                    <td style="width: 70%;">
                        
                        <table style="width: 100%;" border="0">
                            <tr>
                                <td style="width: 50%;">
                                    Desde<br /><?php echo $arrCajasHTML["conf_atencion_desde2"]; ?>
                                </td>
                                <td style="width: 50%;">
                                    Hasta<br /><?php echo $arrCajasHTML["conf_atencion_hasta2"]; ?>
                                </td>
                            </tr>
                        </table>
                        
                    </td>

                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_atencion_dias'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Sólo se aceptarán horarios en los días seleccionados" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">

                        <table style="width: 100%;" border="0">
                            <tr>
                                <td style="width: 50%;">
                                    
                                    <?php $seleccion = ''; if (in_array("1", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                                    <input id="d1" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="1">
                                    <label for="d1"><span></span>Lunes</label>

                                    <br />

                                    <?php $seleccion = ''; if (in_array("2", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                                    <input id="d2" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="2">
                                    <label for="d2"><span></span>Martes</label>

                                    <br />

                                    <?php $seleccion = ''; if (in_array("3", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                                    <input id="d3" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="3">
                                    <label for="d3"><span></span>Miércoles</label>

                                    <br />

                                    <?php $seleccion = ''; if (in_array("4", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                                    <input id="d4" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="4">
                                    <label for="d4"><span></span>Jueves</label>
                                    
                                </td>
                                <td style="width: 50%;">
                                    
                                    <?php $seleccion = ''; if (in_array("5", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                                    <input id="d5" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="5">
                                    <label for="d5"><span></span>Viernes</label>

                                    <br />

                                    <?php $seleccion = ''; if (in_array("6", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                                    <input id="d6" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="6">
                                    <label for="d6"><span></span>Sábado</label>

                                    <br />

                                    <?php $seleccion = ''; if (in_array("0", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                                    <input id="d7" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="0">
                                    <label for="d7"><span></span>Domingo</label>
                                    
                                </td>
                            </tr>
                        </table>
                        
                    </td>

                </tr>

            </table>
            
        </div>

        <br /><br />
            
        <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaConf('onboarding_apertura');">
            <div class="titulo_seccion_conf"> <i class="fa fa-eye" aria-hidden="true"></i> ONBOARDING APERTURA DE CUENTA </div>
        </div>
        
        <div id="onboarding_apertura" style="display: none;">

            <div style="clear: both"></div>

            <!--
            
            <br />

            <div class="FormularioSubtituloMediano" style="float: left;"> <i class="fa fa-sliders" aria-hidden="true"></i> Validación Rekognition (AWS) </div>

            <div style="clear: both"></div>

            <br />

            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php $strClase = "FilaBlanca"; ?>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_rekognition'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_rekognition"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_rekognition_key'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_rekognition_key"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_rekognition_secret'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_rekognition_secret"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_rekognition_region'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_rekognition_region"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_rekognition_similarity'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_rekognition_similarity"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <i class='fa fa-angle-double-right' aria-hidden='true'></i> Imágenes Verificación Facial
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Seleccione los documentos Onboarding que serán objeto de la verificación de Reconocimiento Facial. Sólo se listan los documentos que se hayan marcado como mandatorios; No puede seleccionar el mismo documento para ambos casos." data-balloon-pos="right"> </span>

                    </td>

                    <td style="width: 70%;">

                        <table borrder="0" width="100%">
                            <tr>
                                <td style="width: 45%;">
                                    <?php
                                    if(isset($arrDataDocumento[0]) && count($arrDataDocumento[0]) > 0)
                                    {
                                        $conf_doc_validacion1 = "";

                                        if(isset($arrRespuesta[0]["conf_doc_validacion1"]))
                                        { 
                                            $conf_doc_validacion1 = $arrRespuesta[0]["conf_doc_validacion1"];
                                        }

                                        echo html_select('conf_doc_validacion1', $arrDataDocumento, 'documento_id', 'documento_nombre', 'SINSELECCIONAR', $conf_doc_validacion1);
                                    }
                                    else
                                    {
                                        echo $this->lang->line('TablaNoRegistros');
                                    }
                                    ?>
                                </td>

                                <td style="width: 10%; text-align: center;">
                                    <=Versus=>
                                </td>

                                <td style="width: 45%;">
                                    <?php
                                    if(isset($arrDataDocumento[0]) && count($arrDataDocumento[0]) > 0)
                                    {
                                        $conf_doc_validacion2 = "";

                                        if(isset($arrRespuesta[0]["conf_doc_validacion2"]))
                                        { 
                                            $conf_doc_validacion2 = $arrRespuesta[0]["conf_doc_validacion2"];
                                        }

                                        echo html_select('conf_doc_validacion2', $arrDataDocumento, 'documento_id', 'documento_nombre', 'SINSELECCIONAR', $conf_doc_validacion2);
                                    }
                                    else
                                    {
                                        echo $this->lang->line('TablaNoRegistros');
                                    }
                                    ?>
                                </td>

                            </tr>

                        </table>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <i class='fa fa-angle-double-right' aria-hidden='true'></i> Texto respuesta Imágenes no coinciden
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_rekognition_texto_fallo"]; ?>
                    </td>

                </tr>

            </table>

            <br />
            -->
            
            <br />

            <div class="FormularioSubtituloMediano" style="float: left;"> <i class="fa fa-sliders" aria-hidden="true"></i> Parámetros permitidos en la imágenes </div>

            <div style="clear: both"></div>

            <br />

            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_img_width_max'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_img_width_max"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_img_width_min'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_img_width_min"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_img_height_max'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_img_height_max"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_img_height_min'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_img_height_min"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_img_peso'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_img_peso"]; ?>
                    </td>

                </tr>

            </table>

            <br /><br />

            <div class="FormularioSubtituloMediano" style="float: left;"> <i class="fa fa-sliders" aria-hidden="true"></i> Respuesta al Solicitante </div>

            <div style="clear: both"></div>

            <br />

            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        Texto Puntual de Respuesta (Máx 280 car.)
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_texto_respuesta"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        Al completar el registro ¿se envía correo?
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Puede editar el contenido en el módulo de Plantillas de Correos" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_onboarding_correo"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <i class='fa fa-angle-double-right' aria-hidden='true'></i> (Opcional) Seleccione los PDF a adjuntar
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Es opcional puede proceder sin adjuntar PDFs. Los documentos listados corresponden a los configurados en el apartado de Tipos de Registro y fueron marcados como 'Se Envía'" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        <?php
                        if(isset($arrDataDocumentoEnviar[0]) && count($arrDataDocumentoEnviar[0]) > 0)
                        {
                            $i = 0;
                            $checked = '';

                            foreach ($arrDataDocumentoEnviar as $key => $value) 
                            {
                                if(in_array($value["documento_id"], $arrDocs))
                                {
                                    $checked = 'checked="checked"';
                                }
                                else
                                {
                                    $checked = '';
                                }

                                echo '<input id="documento' . $i , '" type="checkbox" name="conf_onboarding_docs[]" '. $checked .' value="' . $value["documento_id"] . '">';
                                echo '<label for="documento' . $i , '"><span></span>' . $value["documento_nombre"] . '</label>';
                                echo '<br />';

                                $i++;
                            }
                        }
                        else
                        {
                            echo $this->lang->line('TablaNoRegistros');
                        }
                        ?>
                    </td>

                </tr>

            </table>

            <br /><br />

            <div class="FormularioSubtituloMediano" style="float: left;"> <i class="fa fa-sliders" aria-hidden="true"></i> Configuración Token del Registro </div>

            <div style="clear: both"></div>

            <br />

            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_token_dimension'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_token_dimension_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_token_dimension"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_token_otp'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_token_otp_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_token_otp"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_token_validez'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_token_validez_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_token_validez"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_token_texto'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_token_texto"]; ?>
                    </td>

                </tr>

            </table>

            <br /><br />

            <div class="FormularioSubtituloMediano" style="float: left;"> <i class="fa fa-sliders" aria-hidden="true"></i> Configuración Web Service JWT </div>

            <div style="clear: both"></div>

            <br />

            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_jwt_ws_uri'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_jwt_ws_uri_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_jwt_ws_uri"]; ?>
                        <br />
                        <span style="font-weight: bold;" class="EnlaceSimple" onclick="Ajax_CargarAccion_Test_WS('conf_jwt_ws_uri')">
                            <i class="fa fa-plug" aria-hidden="true"></i> Test URI WS
                        </span>

                        <br /><br />

                        <span style="font-weight: bold;" class="EnlaceSimple" onclick="MostrarOcultarJWT();">
                            <?php echo $this->lang->line('MostrarOcultar'); ?>
                        </span>

                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_jwt_client_secret'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_jwt_client_secret"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_jwt_username'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_jwt_username"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_jwt_password'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_jwt_password"]; ?>
                    </td>

                </tr>

            </table>

            <br /><br />

            <div class="FormularioSubtituloMediano" style="float: left;"> <i class="fa fa-sliders" aria-hidden="true"></i> Intentos Captura Widgets </div>

            <div style="clear: both"></div>

            <br />

            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_captura_intentos'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_captura_intentos_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_captura_intentos"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_captura_intentos_texto'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_captura_intentos_texto"]; ?>
                    </td>

                </tr>

            </table>

            <br /><br />

            <div class="FormularioSubtituloMediano" style="float: left;"> <i class="fa fa-plug" aria-hidden="true"></i> Configuración Web Service Consulta COBIS y SEGIP </div>

            <div style="clear: both"></div>

            <br />

            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_cobis_ws_uri'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_soa_fie_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_cobis_ws_uri"]; ?>
                        <br />
                        <span style="font-weight: bold;" class="EnlaceSimple" onclick="Ajax_CargarAccion_Test_WS('conf_cobis_ws_uri')">
                            <i class="fa fa-plug" aria-hidden="true"></i> Test URI WS
                        </span>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_cobis_mandatorio'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_cobis_mandatorio_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_cobis_mandatorio"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_cobis_tipo_error'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_cobis_tipo_error_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('conf_cobis_tipo_error', $arrRespuesta[0]["conf_cobis_tipo_error"], 'segip_codigo_respuesta', -1, -1, -1, 'SINSELECCIONAR'); ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_cobis_texto'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_cobis_texto"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_segip_texto'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_segip_texto"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_segip_mandatorio'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_segip_mandatorio_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_segip_mandatorio"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_segip_intentos'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_segip_intentos_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_segip_intentos"]; ?>
                    </td>

                </tr>

            </table>

            <br /><br />

            <div class="FormularioSubtituloMediano" style="float: left;"> <i class="fa fa-plug" aria-hidden="true"></i> Configuración Web Service Prueba de Vida </div>

            <div style="clear: both"></div>

            <br />

            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_life_ws_uri'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_soa_fie_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_life_ws_uri"]; ?>
                        <br />
                        <span style="font-weight: bold;" class="EnlaceSimple" onclick="Ajax_CargarAccion_Test_WS('conf_life_ws_uri')">
                            <i class="fa fa-plug" aria-hidden="true"></i> Test URI WS
                        </span>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_life_tipo_error'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_life_tipo_error_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('conf_life_tipo_error', $arrRespuesta[0]["conf_life_tipo_error"], 'segip_codigo_respuesta', -1, -1, -1, 'SINSELECCIONAR'); ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_life_texto'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_life_texto"]; ?>
                    </td>

                </tr>

            </table>

            <br /><br />

            <div class="FormularioSubtituloMediano" style="float: left;"> <i class="fa fa-plug" aria-hidden="true"></i> Configuración Resultado OCR </div>

            <div style="clear: both"></div>

            <br />

            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_ocr_ws_uri'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_soa_fie_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_ocr_ws_uri"]; ?>
                        <br />
                        <span style="font-weight: bold;" class="EnlaceSimple" onclick="Ajax_CargarAccion_Test_WS('conf_ocr_ws_uri')">
                            <i class="fa fa-plug" aria-hidden="true"></i> Test URI WS
                        </span>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_ocr_porcentaje'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_ocr_porcentaje_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_ocr_porcentaje"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_ocr_tipo_error'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_ocr_tipo_error_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('conf_ocr_tipo_error', $arrRespuesta[0]["conf_ocr_tipo_error"], 'segip_codigo_respuesta', -1, -1, -1, 'SINSELECCIONAR'); ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_ocr_texto'); ?>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_ocr_texto"]; ?>
                    </td>

                </tr>

            </table>
        
        </div>
        
        <br /><br />
            
        <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaConf('flujo_cobis');">
            <div class="titulo_seccion_conf"> <i class="fa fa-eye" aria-hidden="true"></i> FLUJO REGISTRO COBIS </div>
        </div>
        
        <div id="flujo_cobis" style="display: none;">
            
            <br /><br />

            <div class="FormularioSubtituloMediano" style="float: left;"> <i class="fa fa-sliders" aria-hidden="true"></i> Configuración Intentos flujo y Procesar clientes activos </div>

            <div style="clear: both"></div>

            <br />

            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php $strClase = "FilaBlanca"; ?>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 70%;">
                        
                        <table style="width: 100%;" border="0">
                            <tr>
                                <td style="width: 25%; font-weight: bold;">
                                    <?php echo $this->lang->line('conf_f_cobis_intentos'); ?>
                                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_f_cobis_intentos_ayuda'); ?>" data-balloon-pos="right"> </span>
                                    <br />
                                    <?php echo $arrCajasHTML["conf_f_cobis_intentos"]; ?>
                                </td>

                                <td style="width: 25%; font-weight: bold;">
                                    <?php echo $this->lang->line('conf_f_cobis_intentos_tiempo'); ?>
                                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_f_cobis_intentos_tiempo_ayuda'); ?>" data-balloon-pos="right"> </span>
                                    <br />
                                    <?php echo $arrCajasHTML["conf_f_cobis_intentos_tiempo"]; ?>

                                </td>
                                
                                <td style="width: 25%; font-weight: bold;">
                                    <?php echo $this->lang->line('conf_f_cobis_intentos_operativo'); ?>
                                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_f_cobis_intentos_operativo_ayuda'); ?>" data-balloon-pos="right"> </span>
                                    <br />
                                    <?php echo $arrCajasHTML["conf_f_cobis_intentos_operativo"]; ?>

                                </td>

                                <td style="width: 25%; font-weight: bold;">
                                    <?php echo $this->lang->line('conf_f_cobis_procesa_activo'); ?>
                                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_f_cobis_procesa_activo_ayuda'); ?>" data-balloon-pos="top"> </span>
                                    <br />
                                    <?php echo $arrCajasHTML["conf_f_cobis_procesa_activo"]; ?>
                                </td>
                            </tr>
                        </table>
                        
                    </td>
                    
                </tr>
                
            </table>
                
            <br /><br />

            <div class="FormularioSubtituloMediano" style="float: left;"> <i class="fa fa-sliders" aria-hidden="true"></i> Configuración Web Services </div>

            <div style="clear: both"></div>

            <br />

            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
            
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_f_cobis_header'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_f_cobis_header_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_f_cobis_header"]; ?>
                        <br />

                        <span style="font-weight: bold;" class="EnlaceSimple" onclick="MostrarOcultarCobisHeader();">
                            <?php echo $this->lang->line('MostrarOcultar'); ?>
                        </span>
                    </td>

                </tr>
                
            </table>
            
            <br />
                
            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_f_cobis_uri_cliente_cobis'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_f_cobis_uri_cliente_cobis"]; ?>
                        <br />
                        <span style="font-weight: bold;" class="EnlaceSimple" onclick="Ajax_CargarAccion_Test_WS('conf_f_cobis_uri_cliente_cobis')">
                            <i class="fa fa-plug" aria-hidden="true"></i> Test URI WS
                        </span>
                    </td>

                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_f_cobis_uri_cliente_ci'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_f_cobis_uri_cliente_ci"]; ?>
                        <br />
                        <span style="font-weight: bold;" class="EnlaceSimple" onclick="Ajax_CargarAccion_Test_WS('conf_f_cobis_uri_cliente_ci')">
                            <i class="fa fa-plug" aria-hidden="true"></i> Test URI WS
                        </span>
                    </td>

                </tr>
                
            </table>
            
            <br />
                
            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_f_cobis_uri_cliente_alta'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_f_cobis_uri_cliente_alta"]; ?>
                        <br />
                        <span style="font-weight: bold;" class="EnlaceSimple" onclick="Ajax_CargarAccion_Test_WS('conf_f_cobis_uri_cliente_alta')">
                            <i class="fa fa-plug" aria-hidden="true"></i> Test URI WS
                        </span>
                    </td>

                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_f_cobis_uri_cliente_alta_params'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_f_cobis_uri_cliente_alta_params_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_f_cobis_uri_cliente_alta_params"]; ?>
                        
                        <table style="width: 100%;" border="0">
                            <tr>
                                <td style="width: 70%; text-align: left; font-weight: bold;"><span id="conf_f_cobis_uri_cliente_alta_params_txt"></span></td>
                                <td style="width: 30%; text-align: right; font-style: italic;">
                                    <span style="font-weight: bold;" class="EnlaceSimple" onclick="check_json_params('conf_f_cobis_uri_cliente_alta_params');"> Validar sintaxis </span>
                                </td>
                            </tr>
                        </table>
                        
                    </td>

                </tr>
                
            </table>
            
            <br />
                
            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_f_cobis_uri_apertura'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_f_cobis_uri_apertura"]; ?>
                        <br />
                        <span style="font-weight: bold;" class="EnlaceSimple" onclick="Ajax_CargarAccion_Test_WS('conf_f_cobis_uri_apertura')">
                            <i class="fa fa-plug" aria-hidden="true"></i> Test URI WS
                        </span>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_f_cobis_uri_apertura_params'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_f_cobis_uri_apertura_params_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_f_cobis_uri_apertura_params"]; ?>
                        
                        <table style="width: 100%;" border="0">
                            <tr>
                                <td style="width: 70%; text-align: left; font-weight: bold;"><span id="conf_f_cobis_uri_apertura_params_txt"></span></td>
                                <td style="width: 30%; text-align: right; font-style: italic;">
                                    <span style="font-weight: bold;" class="EnlaceSimple" onclick="check_json_params('conf_f_cobis_uri_apertura_params');"> Validar sintaxis </span>
                                </td>
                            </tr>
                        </table>
                        
                    </td>

                </tr>
                
            </table>
        
        </div>
        
        <br /><br />
        
        <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaConf('onboarding_credito');">
            <div class="titulo_seccion_conf"> <i class="fa fa-eye" aria-hidden="true"></i> ONBOARDING SOLICITUD DE CRÉDITO </div>
        </div>
        
        <div id="onboarding_credito" style="display: none;">

            <div style="clear: both"></div>

            <br />

            <div class="FormularioSubtituloMediano" style="float: left;"> <i class="fa fa-sliders" aria-hidden="true"></i> Solicitud Web </div>

            <div style="clear: both"></div>

            <br />

            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php $strClase = "FilaBlanca"; ?>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_credito_texto'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_credito_texto_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_credito_texto"]; ?>
                    </td>

                </tr>

            </table>

            <br />

            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_credito_notificar_email'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_credito_notificar_email_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_credito_notificar_email"]; ?>
                    </td>

                </tr>

            </table>

            <br />

            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_credito_notificar_sms'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_credito_notificar_sms_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_credito_notificar_sms"]; ?>
                    </td>

                </tr>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_credito_notificar_texto'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_credito_notificar_texto_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_credito_notificar_texto"]; ?>
                    </td>

                </tr>

            </table>

            <br />

            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_credito_tipo_cambio'); ?>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_credito_tipo_cambio"]; ?>
                    </td>

                </tr>

            </table>

        </div>
            
        <br /><br />
            
        <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaConf('calculo_cuota');">
            <div class="titulo_seccion_conf"> <i class="fa fa-eye" aria-hidden="true"></i> CÁLCULO DE LA CUOTA </div>
        </div>
        
        <div id="calculo_cuota" style="display: none;">

            <div style="clear: both"></div>

            <br /><br />

            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php $strClase = "FilaBlanca"; ?>

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_cuota_tasa_seguro'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_cuota_tasa_seguro_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_cuota_tasa_seguro"]; ?>
                    </td>

                </tr>

            </table>
        
        </div>
        
        <br /><br />
            
        <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaConf('integracion_ad');">
            <div class="titulo_seccion_conf"> <i class="fa fa-eye" aria-hidden="true"></i> AUTENTICACIÓN ACTIVE DIRECTORY </div>
        </div>
        
        <div id="integracion_ad" style="display: none;">

            <div style="clear: both"></div>

            <br /><br />

            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php $strClase = "FilaBlanca"; ?>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_ad_activo'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_ad_activo_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_ad_activo"]; ?>
                    </td>

                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_ad_host'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_ad_host_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_ad_host"]; ?>
                    </td>

                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_ad_dominio'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_ad_dominio_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_ad_dominio"]; ?>
                    </td>

                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_ad_dn'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_ad_dn_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">                
                        <?php echo $arrCajasHTML["conf_ad_dn"]; ?>
                    </td>

                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_ad_test_user_ayuda'); ?>
                    </td>

                    <td style="width: 70%;">
                        
                        <table style="width: 100%;" border="0">
                            <tr class="<?php echo $strClase; ?>">
                                <td style="width: 50%; text-align: left; font-style: italic;">
                                    <?php echo $this->lang->line('conf_ad_test_user'); ?>
                                    <?php echo $arrCajasHTML["conf_ad_test_user"]; ?>
                                    <br />
                                    <span style="font-weight: bold;" class="EnlaceSimple" onclick="Ajax_CargarAccion_Test_WS('conf_ad_activo')">
                                        <i class="fa fa-plug" aria-hidden="true"></i> Test conexión AD
                                    </span>
                                </td>
                                <td style="width: 50%; text-align: left; font-style: italic;">
                                    <?php echo $this->lang->line('conf_ad_test_pass'); ?>
                                    <?php echo $arrCajasHTML["conf_ad_test_pass"]; ?>
                                    <br />
                                    <span style="font-weight: bold;" class="EnlaceSimple" onclick="MostrarOcultarADpass();">
                                        <?php echo $this->lang->line('MostrarOcultar'); ?>
                                    </span>
                                </td>
                                </td>
                            </tr>
                        </table>
                    </td>

                </tr>

            </table>
        
        </div>
        
        <br /><br />
            
        <div style="font-weight: bold;" class="EnlaceSimple" onclick="VerTablaConf('servicio_sms');">
            <div class="titulo_seccion_conf"> <i class="fa fa-eye" aria-hidden="true"></i> SERVICIO WEB ENVÍO SMS </div>
        </div>
        
        <div id="servicio_sms" style="display: none;">

            <div style="clear: both"></div>

            <br /><br />

            <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('conf_credito_notificar_sms_uri'); ?>
                        <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_credito_notificar_sms_uri_ayuda'); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%;">
                        <?php echo $arrCajasHTML["conf_credito_notificar_sms_uri"]; ?>
                    </td>
                    
                </tr>  
                    
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        Parámetros por defecto
                    </td>

                    <td style="width: 70%;">                
                        <table style="width: 100%;" border="0">
                            <tr>
                                
                                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                                <tr class="<?php echo $strClase; ?>">

                                    <td style="width: 35%; font-weight: bold; text-align: left;">
                                        <?php echo $this->lang->line('conf_sms_name_plantilla'); ?>
                                        <?php echo $arrCajasHTML["conf_sms_name_plantilla"]; ?>
                                    </td>
                                    
                                    <td style="width: 35%; font-weight: bold; text-align: left;">
                                        <?php echo $this->lang->line('conf_sms_channelid'); ?>
                                        <?php echo $arrCajasHTML["conf_sms_channelid"]; ?>
                                    </td>

                                    <td style="width: 30%; font-weight: bold; text-align: left; font-style: italic;">                
                                        Celular Test
                                        <?php echo $arrCajasHTML["sms_cel_test"]; ?>
                                        <br />
                                        <span style="font-weight: bold;" class="EnlaceSimple" onclick="Ajax_CargarAccion_Test_WS('conf_credito_notificar_sms_uri')">
                                            <i class="fa fa-plug" aria-hidden="true"></i> Test URI WS
                                        </span>
                                    </td>

                                </tr>
                                
                            </tr>
                        </table>
                        
                    </td>

                </tr>
                
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">

                    <td style="width: 30%; font-weight: bold;">
                        Proceso de Onboarding Apertura de Cuenta
                        <br />
                        <i>Configurar habilitar validación PIN, vigencia del PIN SMS y bloqueo</i>
                    </td>

                    <td style="width: 70%;">                
                        <table style="width: 100%;" border="0">
                            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 50%; font-weight: bold;">
                                    <?php echo $this->lang->line('conf_sms_onb_ambiente'); ?>
                                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_sms_onb_ambiente_ayuda'); ?>" data-balloon-pos="right"> </span>
                                    
                                    <?php echo $arrCajasHTML["conf_sms_onb_ambiente"]; ?>
                                    
                                </td>
                                
                                <td style="width: 50%; font-weight: bold;">
                                    <?php echo $this->lang->line('conf_sms_tiempo_validez'); ?>
                                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_sms_tiempo_validez_ayuda'); ?>" data-balloon-pos="right"> </span>
                                    
                                    <?php echo $arrCajasHTML["conf_sms_tiempo_validez"]; ?>
                                    
                                </td>

                            </tr>

                            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 50%; font-weight: bold;">
                                    <?php echo $this->lang->line('conf_sms_permitido_cantidad'); ?>
                                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_sms_permitido_cantidad_ayuda'); ?>" data-balloon-pos="right"> </span>
                                    
                                    <?php echo $arrCajasHTML["conf_sms_permitido_cantidad"]; ?>
                                </td>
                                
                                <td id="td_conf_sms_permitido_tiempo" style="width: 50%; font-weight: bold;">
                                    <?php echo $this->lang->line('conf_sms_permitido_tiempo'); ?>
                                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon=" <?php echo $this->lang->line('conf_sms_permitido_tiempo_ayuda'); ?>" data-balloon-pos="left"> </span>
                                    
                                    <?php echo $arrCajasHTML["conf_sms_permitido_tiempo"]; ?>
                                </td>

                            </tr>
                            
                            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td colspan="2" style="width: 50%; font-weight: bold;">
                                    <?php echo $this->lang->line('conf_sms_permitido_txt_error'); ?>
                                    <?php echo $arrCajasHTML["conf_sms_permitido_txt_error"]; ?>
                                </td>

                            </tr>
                        </table>
                    </td>

                </tr>
                
                            

            </table>
        
        </div>
        
        </form>
        
        <div style="clear: both"></div>
        
        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Menu/Principal');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>
        
        

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('conf_general_Pregunta'); ?></div>
        
            <div style="clear: both"></div>
        
            <br />

        <div class="PreguntaConfirmar">
            <?php echo $this->lang->line('PreguntaContinuar'); ?>
        </div>

        <div class="Botones2Opciones">
            <a onclick="OcultarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a id="btnGuardarDatosLista" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>
		
		<br />

        <?php if (isset($respuesta)) { ?>
            <div class="mensajeBD"> 
                <div style="padding: 15px;">
                    <?php echo $respuesta ?>
                </div>
            </div>
        <?php } ?>

        <div id="divErrorListaResultado" class="mensajeBD"> </div>
		
    </div>
</div>