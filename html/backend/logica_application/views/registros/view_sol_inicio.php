<script type="text/javascript">
<?php
    $estructura_id = $arrRespuesta[0]['sol_id'];
    $vista_actual = $arrRespuesta[0]['sol_ultimo_paso'];
    $codigo_rubro = $arrRespuesta[0]['camp_id'];
    $codigo_evaluacion = $arrRespuesta[0]['sol_evaluacion'];
    $prospecto_desembolso_monto = $arrRespuesta[0]['sol_desembolso_monto'];
?>

    $(document).ready(function(){ 
        $('.evaluacion').find("input[type=text]").each(function(ev)
        {
            $(this).attr("placeholder", " :: Registrar Justificación ::");
        });
        
        $('#divContenidoGeneral').css('padding-bottom','100px');
        
        $('#registro_num_proceso').on('click', function () {
            if($(this).val() == 0)
            {
                $(this).select();
            }
        });
        
        validateSelRubros();
        check_sol_trabajo_actividad_pertenece();
        check_sol_con_trabajo_actividad_pertenece();
        
        check_registro_num_proceso();
    });

    $('#registro_num_proceso').on('keyup', function(){
        check_registro_num_proceso();
    });
    /* $('#registro_num_proceso').on('keyup change', function(){
        check_registro_num_proceso();
    }); */
    
    function check_registro_num_proceso()
    {
        var valor = parseInt($("#registro_num_proceso").val() || 0);
        
        valor = valor.toString();
        
        /*cambio realizado 23/02/2023*/
        $("#jdamonto").addClass("jdamonto-off");
        $("#jdamonto").removeClass("jdamonto-on");
        $('#prospecto_desembolso_monto').val(0);


        $("#msg-error").html("");
        $("#jdamonto").html("0.00");
        $("#msg-error").hide();
        /* fin cambio*/

        if(!( /[^0-9]/.test( valor ) || valor.length != <?php echo (int)$this->lang->line('registro_num_proceso_cantidad'); ?>))
        {
            check_credit_validation(valor); // agregado 23/02/2023
            $('#registro_num_proceso_button').prop('disabled', false);
            
            //$('#registro_num_proceso_label_error').hide(); // comentado 23/02/2023
            //$('#registro_num_proceso_label_ok').show(); // comentado 23/02/2023
        }
        else
        {
            $('#registro_num_proceso_button').prop('disabled', true);
            
            //$('#registro_num_proceso_label_ok').hide();  // comentado 23/02/2023
            //$('#registro_num_proceso_label_error').show();  // comentado 23/02/2023
        }
    }
    
    function VerNumOperacion()
    {
        $("#registro_num_proceso").val($("#aux_nro_operacion").val());
        
        $("#pregunta_opcion").val("numero_operacion");
        $("#div_num_operacion").modal();
        
        check_registro_num_proceso();
    }

    // funcion agregada 23/02/2023
    function check_credit_validation(codigo_operacion) {
        let baseUri = '/Registros/Principal/jsonope';
        <?PHP
        //$ext = trim($arrRespuesta[0]['general_ci']);
        $ext = trim($arrRespuesta[0]['sol_ci']).$arrRespuesta[0]['sol_extension'].'.';
        $ext = str_replace(".","",$ext);
        $ext = str_replace(" ","",$ext);
        ?>
        $("#msg-error").hide();
        let documento_del_cliente =  '<?php echo $ext?>';

        $.ajax({
            url: `${baseUri}`,
            type: 'get',
            data: {
                customerDocumentNumber: documento_del_cliente,
                creditOperation: codigo_operacion,
            },
            dataType: 'json',
            success: function (response) {
                $("#jdamonto").removeClass("jdamonto-off");
                $("#jdamonto").removeClass("jdamonto-on");
                $("#jdamonto").removeClass("jdamonto-erro");

                let numero =0;
                if(
                    typeof response.respapi !== 'undefined'
                    && typeof response.respapi.result !== 'undefined'
                    && typeof response.respapi.result.disbursedAmount !== 'undefined'
                ){
                    numero = parseInt(response.respapi.result.disbursedAmount);
                }
                //console.log("Numero total: "+numero);

                if(response.res==1){
                    let errorval = 0;
                    let errormsg = "";

                    let typeMessage = "BLOCK";
                    if(typeof response.respapi !== 'undefined'
                        && typeof response.respapi.result !== 'undefined'
                        && typeof response.respapi.result.typeMessage !== 'undefined'){
                        typeMessage = response.respapi.result.typeMessage;
                        errormsg = response.respapi.result.message;
                    }else{
                        errormsg = "Error no definido";
                    }

                    if(typeMessage =="BLOCK"){
                        //errormsg = response.respapi.result.message;
                        errorval=1;
                        $('#jdamonto').html("");
                    }else if(typeMessage =="INFO"){
                        //errormsg = response.respapi.result.message;
                        errorval=1;

                        let monto1 = numero;
                        monto1 = monto1/100;
                        $('#jdamonto').html(new Intl.NumberFormat('en-US',{ minimumFractionDigits: 2 }).format(monto1));
                        $("#jdamonto").addClass("jdamonto-off");
                    }else{
                        if(numero > 0){
                            let monto1 = numero;
                            monto1 = monto1/100;
                            $('#prospecto_desembolso_monto').val(monto1);
                            $('#jdamonto').html(new Intl.NumberFormat ('en-US',{ minimumFractionDigits: 2 }).format(monto1));

                            $("#jdamonto").addClass("jdamonto-on");
                            $("#registro_num_proceso_button").show();
                        }else{
                            errorval=1;
                            errormsg = "El monto recuperado es 0 y/o se optuvo un error desconocido :"+response.respapi.message;
                            $("#registro_num_proceso_button").hide();
                        }
                    }

                    /**
                     * mostrar error
                     */
                    if(errorval==1){
                        $("#msg-error").html(errormsg);
                        $("#msg-error").show();
                        $('#prospecto_desembolso_monto').val('0');
                        $("#registro_num_proceso_button").hide();
                    }else{
                        $("#msg-error").html("");
                        $("#msg-error").hide();
                    }
                }else if(response.res==2){
                    console.log(response.msg);
                    $("#msg-error").html(response.msg);
                    $("#msg-error").show();
                    $('#prospecto_desembolso_monto').val('0');
                    $('#jdamonto').html("");
                    $("#registro_num_proceso_button").hide();
                }else{
                    $("#msg-error").html(response.msg);
                    $("#msg-error").show();
                    $('#prospecto_desembolso_monto').val('0');
                    $('#jdamonto').html("");
                    $("#registro_num_proceso_button").hide();
                }
            }
        });
    }

    function validateSelRubros()
    {
        $('#check_sol_trabajo_actividad_pertenece').prop('checked', false);
        $('#check_sol_con_trabajo_actividad_pertenece').prop('checked', false);
        
        if(parseInt($('#sol_trabajo_actividad_pertenece').val()) > 0)
        {
            $('#check_sol_trabajo_actividad_pertenece').prop('checked', true);
            
            if(parseInt($('#sol_con_trabajo_actividad_pertenece').val()) > 0)
            {
                $('#check_sol_con_trabajo_actividad_pertenece').prop('checked', true);
            }
        }
    }

    $("#check_sol_trabajo_actividad_pertenece").on('change', function(){
        
        check_sol_trabajo_actividad_pertenece();
    });
    
    $("#check_sol_con_trabajo_actividad_pertenece").on('change', function(){
        
        check_sol_con_trabajo_actividad_pertenece();
    });

    function check_sol_trabajo_actividad_pertenece()
    {
        $('#rubro_titular_bloque, #rubro_familiar_label, #rubro_familiar_bloque').slideUp();
        
        if($('#check_sol_trabajo_actividad_pertenece').prop('checked'))
        {
            $('#rubro_titular_bloque').slideDown();
            
            <?php echo ((int)$arrRespuesta[0]['sol_conyugue']==1 && str_replace(' ', '', $arrRespuesta[0]['sol_con_ci'])!='' ? "$('#rubro_familiar_label').slideDown();" : ""); ?>
            
            check_sol_con_trabajo_actividad_pertenece();
        }
        else
        {
            $('#check_sol_con_trabajo_actividad_pertenece').prop('checked', false);
        }
    }
    
    function check_sol_con_trabajo_actividad_pertenece()
    {
        if(1 == <?php echo ((int)$arrRespuesta[0]['sol_conyugue']==1 && str_replace(' ', '', $arrRespuesta[0]['sol_con_ci'])!='' ? 1 : 0); ?>)
        {
            if($('#check_sol_con_trabajo_actividad_pertenece').prop('checked'))
            {
                $('#rubro_familiar_bloque').slideDown();
            }
            else
            {
                $('#rubro_familiar_bloque').slideUp();
            }
        }
    }
    
    function EnviarAuxiliar(estructura_id, codigo_rubro, home_ant_sig, tipo_registro="0")
    {
        var vista_actual = "datos_generales";
        var sin_guardar = 1;

        var strParametros = "&estructura_id=" + estructura_id + "&codigo_rubro=" + codigo_rubro + "&vista_actual=" + vista_actual + "&home_ant_sig=" + home_ant_sig + "&sin_guardar=" + sin_guardar + "&tipo_registro=" + tipo_registro;
        strParametros += "&prospecto_desembolso_monto="+$("#prospecto_desembolso_monto").val();
        Ajax_CargadoGeneralPagina("../Pasos/Guardar", "divContenidoGeneral", "divErrorBusqueda", "SIN_FOCUS", strParametros);
    }
    
    function PreguntaAccion(mensaje, accion, valor="-1")
    {
        $('#buttons_pdf_generate').show();
        $('#buttons_pdf_result').hide();
        $('#buttons_pdf_copy').prop('disabled', false);
        
        $("#pregunta_titulo").html(mensaje);
        $("#pregunta_opcion").modal();
        
        $("#pregunta_opcion").val(accion);
        $("#accion_valor").val(valor);
    }
    
    function PreguntaRechazar(mensaje, accion, valor="-1")
    {
        $("#evaluacion_titulo").html(mensaje);
        $("#evaluacion_opcion").modal();
        
        $("#pregunta_opcion").val(accion);
        $("#accion_valor").val(valor);
    }
    
    function PreguntaAprobar(mensaje, accion, valor="-1")
    {
        $("#aprobar_opcion").modal();
        
        $("#pregunta_opcion").val(accion);
        $("#accion_valor").val(valor);
    }
    
    <?php
    // Validar que el ejecutvio ya TENGA ese perfil, si ya lo tiene asignado mostrar mensaje al usuario | 2=Categoría B
    if($this->mfunciones_microcreditos->CheckTipoPerfilEjecutivo($arrRespuesta[0]['ejecutivo_id'], 2))
    {
    ?>
        $("#estructura_regional").on('change', function(){
            
            if($("#estructura_regional").val() != -1)
            {
                $('#agencia_asociada_button').prop('disabled', false);
            }
            else
            {
                $('#agencia_asociada_button').prop('disabled', true);
            }
        });
        
        function PreguntaAgencia(mensaje, accion, valor="-1")
        {
            $("#estructura_regional").val('-1').change();
            $('#agencia_asociada_button').prop('disabled', true);
            
            $("#agencia_opcion").modal();

            $("#pregunta_opcion").val(accion);
            $("#accion_valor").val(valor);
        }
    <?php
    }
    ?>
    
    function RealizaAccion(criterio=0)
    {
        switch ($("#pregunta_opcion").val()) {
            
            case 'asociar_agencia':

                var strParametros = 
                        "&estructura_id=<?php echo $estructura_id; ?>" + 
                        "&codigo_rubro=<?php echo $codigo_rubro; ?>" + 
                        "&vista_actual=datos_generales" + 
                        "&home_ant_sig=0" + 
                        "&sin_guardar=1" + 
                        "&tipo_registro=asociar_agencia" + 
                        "&estructura_regional=" + $("#estructura_regional").val() + 
                        "&codigo_ejecutivo=" +  <?php echo (int)$arrRespuesta[0]['ejecutivo_id']; ?> + 
                        "&codigo_usuario=" + <?php echo (int)$arrRespuesta[0]['agente_codigo']; ?>;
                        
                Ajax_CargadoGeneralPagina("../Pasos/Guardar", "divContenidoGeneral", "divErrorBusqueda", "SIN_FOCUS", strParametros);

                break;
            
            case 'numero_operacion':

                EnviarAuxiliar("<?php echo $estructura_id; ?>", "<?php echo $codigo_rubro; ?>", $("#registro_num_proceso").val(), "sol_numero_operacion");

                break;
            
            case 'sol_rechazar':

                EnviarAuxiliar("<?php echo $estructura_id; ?>", "<?php echo $codigo_rubro; ?>", $("#sol_rechazado_texto").val(), "sol_rechazar");

                break;
                
            case 'sol_aprobar':

                var check_sol_trabajo_actividad_pertenece = ($('#check_sol_trabajo_actividad_pertenece').prop('checked')) ? 1 : 0;
                var check_sol_con_trabajo_actividad_pertenece = ($('#check_sol_con_trabajo_actividad_pertenece').prop('checked')) ? 1 : 0;
                
                var strParametros = 
                        "&estructura_id=<?php echo $estructura_id; ?>" + 
                        "&codigo_rubro=<?php echo $codigo_rubro; ?>" + 
                        "&vista_actual=datos_generales" + 
                        "&home_ant_sig=0" + 
                        "&sin_guardar=1" + 
                        "&tipo_registro=sol_aprobar" + 
                        "&flag_convertir_actividad=" + $("#flag_convertir_actividad").val() + 
                        "&check_sol_trabajo_actividad_pertenece=" + check_sol_trabajo_actividad_pertenece + 
                        "&check_sol_con_trabajo_actividad_pertenece=" + check_sol_con_trabajo_actividad_pertenece + 
                        "&sol_trabajo_actividad_pertenece=" + $("#sol_trabajo_actividad_pertenece").val() + 
                        "&sol_con_trabajo_actividad_pertenece=" + $("#sol_con_trabajo_actividad_pertenece").val();
                        
                Ajax_CargadoGeneralPagina("../Pasos/Guardar", "divContenidoGeneral", "divErrorBusqueda", "SIN_FOCUS", strParametros);

                break;
            
            case 'sol_formulario':
                
                var tipo = 'pdf';

                $.ajax({
                    url: '../SolCred/OTAFormulario',
                    type: 'post',
                    data: {
                        estructura_id:<?php echo $estructura_id; ?>, codigo_ejecutivo:<?php echo ($this->mfunciones_microcreditos->CheckIsMobile() ? 1 : 2); ?>, tipo_registro:tipo
                    },
                    dataType: 'json',
                    success:function(response){
                        var len = response.length;
                        if(len>0)
                        {
                            var pdf_link = '<?php echo $this->config->base_url(); ?>Registros/SolCred/PrepForm/?' + response[0]['armado'];
                            
                            <?php echo ($this->mfunciones_microcreditos->CheckIsMobile() ? "" : "window.open(pdf_link,'_blank'); return false;"); ?>
                            
                            $('#pdf_link').val(pdf_link);
                            $('#pregunta_opcion').val('sol_formulario_open');
                            
                            $('#buttons_pdf_generate').hide();
                            $('#buttons_pdf_result').fadeOut();
                            $('#pregunta_titulo').html('¡Enlace seguro de único acceso generado correctamente! <br /><br /> Puede abrir el enlace o copiarlo para <i>pegarlo</i> en su explorador de forma manual.');
                            $('#pregunta_titulo').fadeIn();
                            $('#buttons_pdf_result').fadeIn();
                        }
                        else
                        {
                            $('#divErrorBusqueda').html('<br />No se generó el formulario, por favor revise el registro e inténtelo nuevamente.<br /><br />');
                            $('html,body').scrollTop(0);
                        }
                    }
                });
                                        
                break;
                
            case 'sol_formulario_open':
            
                    window.open($('#pdf_link').val(),'_blank');
            
                return false;
            
                break;
                
            default:

                break;
        }
    }
    
    function copyToClipboard(text) {
        var sampleTextarea = document.createElement("textarea");
        document.body.appendChild(sampleTextarea);
        sampleTextarea.value = text; //save main text in it
        sampleTextarea.select(); //select textarea contenrs
        document.execCommand("copy");
        document.body.removeChild(sampleTextarea);
    }

    function CopySafeLink(){
        var copyText = document.getElementById("pdf_link");
        copyToClipboard(copyText.value);
        $('#buttons_pdf_copy').prop('disabled', true);
        $('#buttons_pdf_copy').html('<i class="fa fa-check-square-o" aria-hidden="true"></i> Copiado');
    }
    
</script>
<style>
    .jdamonto-on{
        background-color: rgba(227, 253, 235, 1);
        border: 1px solid #578b58;
        color: rgba(60, 118, 61, 1);
        padding: 6px;
        text-align: right;
        font-size: 15px;
        border-radius: 7px;
    }
    .jdamonto-off{
        background-color: #f7f7f7;
        border: 1px solid #a7a7a7;
        color: #777777;
        padding: 6px;
        text-align: right;
        font-size: 15px;
        border-radius: 7px;
    }
    .jdamonto-erro{
        background-color: #f8d7da;
        border: 1px solid #dc3545;
        color: #975057;
        padding: 6px;
        text-align: right;
        font-size: 13px;
        border-radius: 7px;
    }
    .resumen{
        background-color: #fcf8e3;
        border: 1px solid #b1a181;
        width: 100%;
        font-size: 15px !important;
        color: #846d3e;
        border-radius: 7px;
        margin-bottom: 10px;
        padding: 10px;
    }

</style>

    <?php

        $clase_contenido_extra = 'contenido_formulario-nopasos';
        $clase_navbar_extra = 'navbar-nopasos';
    ?>

<nav class="navbar sticky-top <?php echo $clase_navbar_extra; ?>"> <?php echo $this->mfunciones_microcreditos->getContenidoNavApp($estructura_id, "0"); ?> </nav>

<div class="contenido_formulario container <?php echo $clase_contenido_extra; ?>">

    <input type="hidden" name="accion_valor" id="accion_valor" />
    <input type="hidden" name="pdf_link" id="pdf_link" />
    
    <input type="hidden" name="aux_nro_operacion" id="aux_nro_operacion" value="<?php echo $arrRespuesta[0]['registro_num_proceso']; ?>" />

    <div id="divErrorBusqueda" class="mensajeBD"> </div>
    
    <div class='col-sm-6'>

        <div style="text-align: right; clear: both">
            <label class='label-campo texto-centrado panel-heading color-azul' style="padding-bottom: 0px; margin-bottom: 0px; text-align: right;" for=''> Registro Onboarding<br />Solicitud de Crédito <i class="fa fa-cubes" aria-hidden="true"></i> </label>
        </div>
        
        <div style="text-align: right; clear: both">
            <label class='label-campo color-azul' for='' style="text-align: right;"> Registre la información de la Solicitud de Crédito y marque la Evaluación respectiva. Puede Generar el formulario PDF con la información registrada hasta el momento.<br /><br />Para concluir proceda a Consolidar el Registro.</label>
        </div>
        
        <br />
        
        <div class="row">
            <div class="col" style="text-align: center;">
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 5px 0px !important; width: 255px !important; font-size: 13px; height: 35px;" onclick="EnviarAuxiliar('<?php echo $estructura_id; ?>', '<?php echo $codigo_rubro; ?>', '<?php echo str_replace("view_", "", $vista_actual); ?>', '<?php echo $arrRespuesta[0]['ejecutivo_id']; ?>')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Registrar Información </button>
            </div>
        </div>

        <?php
        // Validar que el ejecutvio ya TENGA ese perfil, si ya lo tiene asignado mostrar mensaje al usuario | 2=Categoría B
        if($this->mfunciones_microcreditos->CheckTipoPerfilEjecutivo($arrRespuesta[0]['ejecutivo_id'], 2))
        {
        ?>
            <br />
            <div class="row">
                <div class="col" style="text-align: center;">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 5px 0px !important; width: 255px !important; font-size: 13px; height: 35px;" onclick="PreguntaAgencia('', 'asociar_agencia')"><i class="fa fa-external-link" aria-hidden="true"></i> <?php echo $this->lang->line('ejecutivo_perfil_tipo_accion'); ?> </button>
                </div>
            </div>
        <?php
        }
        ?>

    </div>

    <br />

    <div class='col-sm-6'>
        
        <?php
        if($arrRespuesta[0]["sol_codigo_rubro"] >= 7)
        {
        ?>
            <div class="row">
                <div class="col" style="text-align: center;">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 5px 0px !important; width: 255px !important; font-size: 12px; height: 35px;" onclick="VerNumOperacion();"> <?php echo $this->lang->line('registro_num_proceso_button'); ?> </button>
                </div>
            </div>
        
            <br />
        <?php
        }
        ?>


        <div class="row">
            <div class="col" style="text-align: center;">
                <?PHP if ($arrRespuesta[0]['registro_num_proceso'] !="" && $arrRespuesta[0]['registro_num_proceso']!='0') {?>
                <div id="resumen" class="resumen">
                    <strong>Número de Operación:</strong> <?PHP echo $arrRespuesta[0]['registro_num_proceso']?>
                    <br>
                    <strong>Monto :</strong> <?PHP echo number_format($arrRespuesta[0]['sol_desembolso_monto'], 2, '.', ',');?>
                </div>
                <?PHP }?>
            </div>
        </div>
        <br />
        
        <div class="row">
            <div class="col" style="text-align: center;">
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="margin-right: 5px; border-radius: 0px !important; padding: 5px 0px !important; width: 223px !important; font-size: 13px; height: 35px;" onclick="PreguntaAccion('Generar el formulario PDF de la Solicitud de Crédito con la información registrada hasta el momento.<?php echo ($this->mfunciones_microcreditos->CheckIsMobile() ? '<br />Se generará un enlace seguro de único acceso (OTA) para obtener el Formulario PDF a través de su explorador web por defecto y descargarlo en su dispositivo, una vez generado puede retornar al App. Se recomienda generar desde desktop.' : ''); ?><br /><br />¿Seguro que quiere continuar?', 'sol_formulario')"><i class="fa fa-file-text-o" aria-hidden="true"></i> Generar Formulario PDF </button>
                <span style="text-shadow: 0 0 black;" class="AyudaTooltip" data-balloon-length="medium" data-balloon="<?php echo $this->lang->line('sol_info_formulario'); ?>" data-balloon-pos="left"> </span>
            </div>
        </div>
        
        <br /><br />
        
        <div class="row" style="border: 1px solid #bcbcbc;">
            <div class="col" style="text-align: center;">
                <label class='label-campo texto-centrado' for=''> <?php echo ($codigo_evaluacion!=0 ? "<i class='fa fa-dot-circle-o' aria-hidden='true'></i> " : ""); ?> <?php echo $this->mfunciones_generales->GetValorCatalogo($codigo_evaluacion, 'prospecto_evaluacion'); ?> </label>
            </div>
            <div class="col" style="text-align: center; background-color: #f3f3f3; border-color: #ddd;">
                <label class='label-campo texto-centrado' for=''> EVALUACIÓN </label>
                <span style="text-shadow: 0 0 black;" class="AyudaTooltip" data-balloon-length="medium" data-balloon="Para actualizar la Evaluación de la Solicitud de Crédito, considere todos los elementos e información del mismo para establecer su criterio." data-balloon-pos="left"> </span>
            </div>
        </div>
        
        <div class="row" style="margin-top: 5px;">
            <div class="col" style="text-align: left;">
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 5px 0px !important; width: 95% !important; font-size: 13px; height: 35px;" onclick="PreguntaRechazar('Se procederá a marcar la evaluación de la Solicitud de Crédito como Rechazado registrando la justificación respectiva. <br /><br /> ¿Seguro que quiere continuar?', 'sol_rechazar')"><i class="fa fa-ban" aria-hidden="true"></i> Rechazar </button>
            </div>
            <div class="col" style="text-align: right;">
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 5px 0px !important; width: 95% !important; font-size: 13px; height: 35px;" onclick="PreguntaAprobar('', 'sol_aprobar')"><i class="fa fa-check-square-o" aria-hidden="true"></i> Aprobar </button>
            </div>
        </div>
        
        <br />
        <div class="row" style="border: 1px solid #bcbcbc; padding: 10px 10px;">
            <div class="col" style="text-align: justify; font-style: italic; font-weight: bold; font-size: 12px;">

                <?php

                // [-- Sección ALPHA
                
                $convertir = $this->mfunciones_microcreditos->checkConvertirEstudio($arrRespuesta[0]['sol_codigo_rubro'], $arrRespuesta[0]['sol_actividad_secundaria'], $arrRespuesta[0]['sol_codigo_rubro_sec']);
                
                $icono_info = $convertir->icono_info;
                $flag_convertir_sw = $convertir->flag_convertir_sw;
                $flag_convertir_actividad = $convertir->flag_convertir_actividad;
                $texto_aux_convertir = $convertir->texto_aux_convertir;
                
                // Sección ALPHA --]
        
                echo '<p style="margin-bottom: 5px;">' . $icono_info . ' Asociado a: ' . $arrRespuesta[0]['nombre_agencia'] . ((int)$arrRespuesta[0]["estado_region"]==1 ? '' : ' (Cerrada)') . '</p>';
                
                if((int)$codigo_evaluacion>0)
                {
                    if((int)$codigo_evaluacion == 1)
                    {
                        if(str_replace(' ', '', $arrRespuesta[0]['sol_ultimo_paso']) != 'view_final')
                        {
                            echo '<p style="margin-bottom: 5px; color: #ff0000;">' . $icono_info . 'Registro incompleto. Recomendado completar el registro de la información antes de consolidar.</p>';
                        }

                        if((int)$arrRespuesta[0]['sol_trabajo_actividad_pertenece'] > 0 && $flag_convertir_sw == 1)
                        {
                            if((int)$arrRespuesta[0][($flag_convertir_actividad==1 ? 'sol_codigo_rubro' : 'sol_codigo_rubro_sec')] != (int)$arrRespuesta[0]['sol_trabajo_actividad_pertenece'])
                            {
                                $txt_no_match = '<span style="color: #ff0000;"> ¡El rubro seleccionado para convertir a Estudio de Crédito no coincide con el registrado en la solicitud! Favor verifique y vuelva a "Aprobar". </span>';
                            }
                            else
                            {
                                $txt_no_match = '';
                            }

                            echo '<p style="margin-bottom: 5px;">' . $icono_info . ' Nuevo estudio con la Actividad ' . ($flag_convertir_actividad==1 ? 'Principal' : 'Secundaria') . '.</p>';
                            echo '<p style="margin-bottom: 5px;"> &nbsp;&nbsp; <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> Crear titular, rubro ' . $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]['sol_trabajo_actividad_pertenece'], 'nombre_rubro') . '. ' . $txt_no_match . '</p>';
                            echo ((int)$arrRespuesta[0]['sol_conyugue']==1 && str_replace(' ', '', $arrRespuesta[0]['sol_con_ci'])!='' && (int)$arrRespuesta[0]['sol_con_trabajo_actividad_pertenece']>0 ? '<p style="margin-bottom: 5px;"> &nbsp;&nbsp; <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> Crear unidad familiar, rubro ' . $this->mfunciones_generales->GetValorCatalogo($arrRespuesta[0]['sol_con_trabajo_actividad_pertenece'], 'nombre_rubro') . '.</p>' : '');
                        }
                        else
                        {
                            echo '<p style="margin-bottom: 5px;' . ($flag_convertir_sw == 1 ? ' color: #ff0000;' : '') . '">' . $icono_info . ' SIN CONVERTIR A ESTUDIO DE CRÉDITO.</p>';
                        }
                    }

                    echo $icono_info; ?> Para que se aplique la evaluación efectuada, debe consolidar el registro. <?php echo ((int)$codigo_evaluacion == 1 ? '[--] Importante: El cliente debe firmar el formulario de Solicitud de Crédito antes de consolidar.' : '');
                }
                
                ?>

            </div>
        </div>

    </div>

</div>

<br /><br />

<div class="modal fade" id="pregunta_opcion" role="dialog" <?php echo ($this->mfunciones_microcreditos->CheckIsMobile() ? 'style="top: -120px !important;"' : ''); ?>>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="pregunta_titulo">Seleccione para Continuar</h4>
        </div>
        <div class="modal-body">
            
            <div style="text-align: center;" id="buttons_pdf_generate">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 130px !important;">Cancelar</button>
                <button type="button" class="btn btn-primary" <?php echo ($this->mfunciones_microcreditos->CheckIsMobile() ? '' : 'data-dismiss="modal"'); ?> style="border-radius: 0px !important; padding: 8px 0px !important; width: 150px !important;" onclick="RealizaAccion();">Si, Continuar</button>
            </div>
            
            <div style="text-align: center; display: none" id="buttons_pdf_result">
                <button id="buttons_pdf_copy" type="button" class="btn btn-warning" style="width: 40% !important;" onclick="CopySafeLink();"> <i class="fa fa-clipboard" aria-hidden="true"></i> Copiar </button>
                &nbsp;
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 8px 0px !important; width: 40% !important;" onclick="RealizaAccion();"> Abrir <i class="fa fa-external-link" aria-hidden="true"></i> </button>
                &nbsp;&nbsp;&nbsp;<span style="text-shadow: 0 0 black;" class="AyudaTooltip" data-balloon-length="medium" data-balloon="Si la opción 'Abrir' no se activa por elementos emergentes deshabilitados u otros puede copiar el mismo y abrirlo de forma manual desde su explorador web." data-balloon-pos="left"> </span>
                <br /><br />
                <div style="text-align: center;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 90% !important;">Cerrar ventana</button>
                </div>
            </div>
            
        </div>
  </div>
</div>
</div>

<div class="modal fade" id="evaluacion_opcion" role="dialog">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="evaluacion_titulo">Seleccione para Continuar</h4>
        </div>
        <div class="modal-body">
            <div class="evaluacion" style="text-align: center;">
                
                <label class='label-campo' for='sol_rechazado_texto' style="font-style: italic;">Registrar Justificación</label>
                <input style="width: 100%; height: 18px;" type="text" autocomplete="off" value="<?php echo $arrRespuesta[0]['sol_rechazado_texto'];?>" id="sol_rechazado_texto" name="sol_rechazado_texto" maxlength="150" title="" onkeydown="return (event.keyCode != 13);">

                <br /><br />
                    
                <button type="button" class="btn btn-danger" data-dismiss="modal" style="border-radius: 0px !important; padding: 8px 0px !important; width: 130px !important;" onclick="RealizaAccion();"> Rechazar </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 130px !important;">Cancelar</button>
            
            </div>
        </div>
  </div>
</div>
</div>

<div class="modal fade" id="aprobar_opcion" role="dialog" style="top: -135px !important;">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header" style="padding-bottom: 0px;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="aprobar_titulo" style="font-size: 15px !important;">
                    
                APROBAR SOLICITUD
                
                <?php
                
                // [-- Sección ALPHA --]
                
                echo (str_replace(' ', '', $texto_aux_convertir)=='' ? '' : '<br />' ) . $texto_aux_convertir;
                
                ?>
                
                <br /><br />
                
                <div class="col-sm-<?php echo ($flag_convertir_actividad==1 ? '12' : '6'); ?>">
                    
                    <span style="font-size: 0.8em;"> <i class="fa fa-briefcase" aria-hidden="true"></i> Actividad Principal</span><br />
                    <span style="text-shadow: 0 0 black;" class="AyudaTooltip" data-balloon-length="medium" data-balloon="Este es el rubro registrado para la Solicitud de Crédito. No debe confundirse con los formularios por rubro, ya que este es independiente al estudio de crédito. El listado de Rubros para convertir la 'Solicitud de Crédito' a 'Estudio de Crédito' son los que actualmente se encuentran establecidos en el sistema (con sus formularios respectivos)." data-balloon-pos="right"> </span>
                    Rubro: <?php echo $this->mfunciones_microcreditos->GetValorCatalogo($arrRespuesta[0]['sol_codigo_rubro'], 'aux_rubro'); ?>
                    
                </div>
                
                <?php
                // Mostrar referencia de Actividad Secundaria sólo si cumple con el criterio de rubro principal Agropecuario|Ingreso Fijo y Actividad Secundaria registrada
                if($flag_convertir_actividad == 2)
                {
                ?>
                    <div class="col-sm-5">
                        <span style="font-size: 0.8em;"> <i class="fa fa-briefcase" aria-hidden="true"></i> Actividad Secundaria</span><br />
                        Rubro: <?php echo $this->mfunciones_microcreditos->GetValorCatalogo($arrRespuesta[0]['sol_codigo_rubro_sec'], 'aux_rubro'); ?>
                    </div>
                <?php
                }
                ?>
                
            </h4>
        </div>
        <div class="modal-body">
            <div class="evaluacion" style="text-align: center;">
                
                <?php // Flag que indica si para la conversión se utilizará la Actividad Principal o Secundaria  1=Principal | 2=Secundaria ?>
                <input type="hidden" name="flag_convertir_actividad" id="flag_convertir_actividad" value="<?php echo $flag_convertir_actividad; ?>" />
                
                <?php
                
                if($flag_convertir_sw == 1)
                {
                    $arrCampana = $this->mfunciones_logica->ObtenerCampana(-1);
                    $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrCampana);
                ?>

                    <input id="check_sol_trabajo_actividad_pertenece" type="checkbox" name="check_sol_trabajo_actividad_pertenece" <?php echo ((int)$arrRespuesta[0]['sol_trabajo_actividad_pertenece']>0 ? 'checked="checked"' : ''); ?> value="1">
                    <label class="label-campo texto-centrado" style="margin-left: 0px !important; margin-bottom: 0px !important; padding-top: 0px !important;" for="check_sol_trabajo_actividad_pertenece"><span></span>A. <?php echo ($flag_convertir_actividad==1 ? 'Principal' : 'Secundaria'); ?> ¿Convertir a estudio?</label>

                    <div class="row" style="margin-top: 5px;" id="rubro_titular_bloque">
                        <div class="col" style="text-align: center;">
                           <label class="label-campo" for="">TITULAR:</label>
                        </div>
                        <div class="col" style="text-align: center;">
                            <?php
                            if(isset($arrCampana[0]) && count($arrCampana[0]) > 0)
                            {
                                echo html_select('sol_trabajo_actividad_pertenece', $arrCampana, 'camp_id', 'camp_nombre', '', (int)$arrRespuesta[0][($flag_convertir_actividad==1 ? 'sol_codigo_rubro' : 'sol_codigo_rubro_sec')]);
                            }
                            else
                            {
                                echo $this->lang->line('TablaNoRegistros');
                            }
                            ?>
                        </div>
                    </div>

                    <br />
                    
                    <input id="check_sol_con_trabajo_actividad_pertenece" type="checkbox" name="check_sol_con_trabajo_actividad_pertenece" <?php echo ((int)$arrRespuesta[0]['sol_con_trabajo_actividad_pertenece']>0 ? 'checked="checked"' : ''); ?> value="1">
                    <label id="rubro_familiar_label" class="label-campo texto-centrado" style="margin-left: 0px !important; margin-bottom: 0px !important; padding-top: 0px !important;" for="check_sol_con_trabajo_actividad_pertenece"><span></span>Cónyuge ¿Convertir a U. Familiar?</label>

                    <div class="row" style="margin-top: 5px;" id="rubro_familiar_bloque">
                        <div class="col" style="text-align: center;">
                           <label class="label-campo" for="">U. FAMILIAR:</label>
                        </div>
                        <div class="col" style="text-align: center;">
                            <?php
                            if(isset($arrCampana[0]) && count($arrCampana[0]) > 0)
                            {
                                echo html_select('sol_con_trabajo_actividad_pertenece', $arrCampana, 'camp_id', 'camp_nombre', '', (int)$arrRespuesta[0]['sol_con_trabajo_actividad_pertenece']);
                            }
                            else
                            {
                                echo $this->lang->line('TablaNoRegistros');
                            }
                            ?>
                        </div>
                    </div>

                    <br /><br /><br />
                
                <?php
                }
                ?>
                    
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 130px !important;">Cancelar</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 8px 0px !important; width: 135px !important;" onclick="RealizaAccion();">Si, Aprobar</button>
            
            </div>
        </div>
  </div>
</div>
</div>

<?php
if($arrRespuesta[0]["sol_codigo_rubro"] >= 7)
{
?>

    <div class="modal fade" id="div_num_operacion" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 0px !important;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="evaluacion_titulo"><?php echo $this->lang->line('registro_num_proceso_titulo'); ?> Este valor es requerido para poder consolidar la Solicitud de Crédito.</h4>
            </div>
            <div class="modal-body">
                <div class="evaluacion" style="text-align: center;">

                    <label class='label-campo' for='terceros_observacion' style="font-style: italic;">
                        El valor <?php echo $this->lang->line('registro_num_proceso_validate'); ?>

                        <?php echo $this->lang->line('registro_num_proceso_label'); ?>

                    </label>
                    <input style="width: 100%; height: 18px;" type="number" autocomplete="off" value="<?php echo $arrRespuesta[0]['registro_num_proceso']; ?>" 
                            id="registro_num_proceso" name="registro_num_proceso" maxlength="<?php echo (int)$this->lang->line('registro_num_proceso_cantidad'); ?>" 
                            title="" onkeydown="return (event.keyCode != 13);">

                    <table class="tablaresultados Mayuscula" style="margin-top: 10px !important;width: 100% !important;" border="0">
                        <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
                        <tr class="<?php echo $strClase; ?>">
                            <td style="width: 30%; font-weight: bold;">
                                <?php echo $this->lang->line('prospecto_desembolso_monto'); ?>
                            </td>

                            <td style="width: 70%;">
                                <?php //echo $arrCajasHTML["prospecto_desembolso_monto"]; ?>
                                <input type="hidden" name="prospecto_desembolso_monto" id="prospecto_desembolso_monto" value="<?PHP echo $arrRespuesta[0]['sol_desembolso_monto']?>">
                                <div id="jdamonto" class="jdamonto-off">
                                    <?PHP echo number_format($arrRespuesta[0]['sol_desembolso_monto'], 2, '.', ',');?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div id="msg-error" class="jdamonto-erro">ss</div>
                            </td>
                        </tr>
                    </table>

                    <br /><br />

                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 0px !important; padding: 8px 0px !important; width: 40% !important;">Cancelar</button>
                    <button type="button" id="registro_num_proceso_button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 8px 0px !important; width: 40% !important;" onclick="RealizaAccion(1)"> Registrar </button>
                </div>
            </div>
      </div>
    </div>
    </div>
<?php
}

// Validar que el ejecutvio ya TENGA ese perfil, si ya lo tiene asignado mostrar mensaje al usuario | 2=Categoría B
if($this->mfunciones_microcreditos->CheckTipoPerfilEjecutivo($arrRespuesta[0]['ejecutivo_id'], 2))
{
?>

    <div class="modal fade" id="agencia_opcion" role="dialog" style="top: -135px !important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 0px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="aprobar_titulo" style="font-size: 15px !important;">
                    GESTIONAR AGENCIA ASOCIADA
                    
                    <br />
                    
                    <span style="font-size: 0.85em; font-style: italic;"> <?php echo $icono_info; ?> Puede actualizar la asociación del registro seleccionando el listado de las Agencias que tiene asignadas para supervisar. </span>
                        
                    <span style="font-size: 0.70em; font-style: italic;"><br />Las Solicitudes de Crédito, para los casos No-Asistidos (vía Web) son asignados desde el BackEnd con la Agencia seleccionada por el cliente; los Asistidos, se asocian a la Agencia de la que depende el Oficial de Negocios al momento del registro.</span>
                    
                </h4>
            </div>
            <div class="modal-body">
                
                <div class='col-sm-12' style='text-align: center;'>
                    <label class="label-campo" for="" style='text-align: center; font-size: 1.2em; background-color: #006699; color: #ffffff; padding: 5px 10px; border-radius: 5px; margin: 0px;'> <i class="fa fa-check-circle" aria-hidden="true"></i> <?php echo $arrRespuesta[0]['nombre_agencia'] . ((int)$arrRespuesta[0]["estado_region"]==1 ? '' : ' (Cerrada)'); ?></label>
                </div>
                
                <div class='col-sm-12'>
                    <label class="label-campo" for="estructura_regional" style="font-size: 1em;"> <i class="fa fa-refresh" aria-hidden="true"></i> Cambiar a:</label>
                    <br />
                    <?php

                        if(count($arrAgencias[0]) > 0)
                        {
                            echo html_select('estructura_regional', $arrAgencias, 'estructura_regional_id', 'estructura_regional_nombre', '', '');
                        }
                        else
                        {
                            echo $this->lang->line('TablaNoRegistros');
                        }
                    ?>
                </div>
                
                <div style="clear: both;"></div>
                
                <br /><br />
                
                <div style="text-align: center;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 130px !important;">Cancelar</button>
                    <button id="agencia_asociada_button" type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 0px !important; padding: 8px 0px !important; width: 135px !important;" onclick="RealizaAccion();">Cambiar Agencia</button>
                </div>
                
            </div>
      </div>
    </div>
    </div>

<?php
}
?>