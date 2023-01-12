<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Bandeja/JDAEval/Guardar',
            'divVistaMenuPantalla', 'divErrorListaResultado');

    $("#divCargarFormulario").show();    
    $("#confirmacion").hide();

    function MostrarConfirmación()
    {
        switch ($("#prospecto_jda_eval").val()) {
            case "1":
                $("#jda_eval_valor").html('"<u>APROBADO</u>", se habilitará la opción "Desembolso COBIS" para el registro.');
                break;

            case "2":
                $("#jda_eval_valor").html('"<u>RECHAZADO</u>".<br /><br /><span style="color: #ae0404;">Al marcar esta opción <u>FINALIZARÁ EL FLUJO PARA EL REGISTRO.</u><span>');
                break;

            case "99":
                $("#jda_eval_valor").html('"<u>Devolver al Oficial de Negocios</u>".<br /><br /><span style="color: #ae0404;">Al continuar con esta opción se procederá a marcar el registro como observado y será derivado al Oficial de Negocios, por lo que ya no será visible en la bandeja de "<?php echo $this->lang->line('VerificacionTitulo'); ?>" hasta que se vuelva a consolidar el registro.<span>');
                break;

            default:
                $("#jda_eval_valor").html('"<u>No Evaluado</u>".');
                break;
        }
        
        $("#divCargarFormulario").hide();
        $("#confirmacion").fadeIn(500);
    }
    
    function OcultarConfirmación()
    {
        $("#divCargarFormulario").fadeIn(500);    
        $("#confirmacion").hide();
    }

    <?php
    if((int)$codigo_tipo_persona != 6)
    {
        echo '  $(document).ready(function(){
                    $("#prospecto_jda_eval").append($("<option>", {
                        value: 99,
                        text: "Devolver al Oficial de Negocios"
                    }));
                });';
    }
    ?>

    $('#registro_num_proceso').on('click', function () {
        if($(this).val() == 0)
        {
            $(this).select();
        }
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
            $('#registro_num_proceso_label_error').hide();
            $('#registro_num_proceso_label_ok').show();
        }
        else
        {
            $('#registro_num_proceso_label_ok').hide();
            $('#registro_num_proceso_label_error').show();
        }
    }
    
    check_registro_num_proceso();

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('TituloJDA_Evaluacion'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo str_replace('“Aprobado” o “Rechazado”', '"Aprobado", "Rechazado" o "Devolver al Oficial de Negocios" (opción disponible sólo para Estudio de Crédito)', $this->lang->line('SubJDA_Evaluacion')); ?></div>
  
        <div style="clear: both"></div>
        
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["prospecto_id"])){ echo $arrRespuesta[0]["prospecto_id"]; } ?>" />
            <input type="hidden" name="codigo_tipo_persona" value="<?php echo $codigo_tipo_persona; ?>" />
            
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

            <?php $strClase = "FilaGris"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('prospecto_codigo'); ?>
                </td>

                <td style="width: 70%;">
                    
                    <?php
                    if($arrRespuesta[0]["camp_id"] >= 7 && $arrRespuesta[0]["camp_id"] <= 12)
                    {
                    ?>
                        <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleSolicitudCred('<?php echo $arrRespuesta[0]["prospecto_id"]; ?>', 0)">
                            <?php echo 'SOL_' . $arrRespuesta[0]["prospecto_id"]; ?> <i class="fa fa-external-link" aria-hidden="true"></i>
                        </span>

                    <?php
                    }
                    else
                    {
                    ?>
                        <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleProspecto('<?php echo $arrRespuesta[0]["prospecto_id"]; ?>')">
                            <?php echo PREFIJO_PROSPECTO . $arrRespuesta[0]["prospecto_id"]; ?> <i class="fa fa-external-link" aria-hidden="true"></i>
                        </span>
                    <?php
                    }
                    ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('import_campana'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["camp_nombre"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('import_agente'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["usuario_nombre"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('general_solicitante_corto'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php 
                    echo $arrRespuesta[0]["general_solicitante"];
                    echo " | C.I.:";
                    echo $arrRespuesta[0]["general_ci"];
                    ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    Fecha Consolidado
                </td>

                <td style="width: 70%;">                
                    <?php echo $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($arrRespuesta[0]["prospecto_consolidar_fecha"]); ?>
                </td>

            </tr>
            
        </table>
        
            <br />
            
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">
            
            <?php $strClase = "FilaBlanca"; ?>
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">
                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('registro_num_proceso'); ?>
                    <?php echo $this->lang->line('registro_num_proceso_label'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["registro_num_proceso"]; ?>
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">
                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('prospecto_jda_eval'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["prospecto_jda_eval"]; ?>
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">
                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('prospecto_jda_eval_texto'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["prospecto_jda_eval_texto"]; ?>
                </td>
            </tr>
            
        </table>

        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            
            <?php
            
                $direccion_bandeja = 'Bandeja/Verificacion/Ver';
            
            ?>
            
            <a onclick="Ajax_CargarOpcionMenu('<?php echo $direccion_bandeja; ?>');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('jda_eval_Pregunta'); ?></div>
        
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