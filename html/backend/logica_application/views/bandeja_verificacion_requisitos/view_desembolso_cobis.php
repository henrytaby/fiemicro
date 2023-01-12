<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Bandeja/DesembCOBIS/Guardar',
            'divVistaMenuPantalla', 'divErrorListaResultado');

    $("#divCargarFormulario").show();    
    $("#confirmacion").hide();

    function MostrarConfirmación()
    {
        var monto = parseFloat($("#prospecto_desembolso_monto").val() || 0);
        
        $("#DesembCOBIS_valor").html(monto.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
        $("#divCargarFormulario").hide();
        $("#confirmacion").fadeIn(500);
    }
    
    function OcultarConfirmación()
    {
        $("#divCargarFormulario").fadeIn(500);    
        $("#confirmacion").hide();
    }
    
    function Ajax_CargarAccion_DetalleSolicitudCred(codigo, vista) {
        var strParametros = "&codigo=" + codigo + "&vista=" + vista;
        Ajax_CargadoGeneralPagina('SolWeb/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function UseAmount(monto)
    {
        if($('#prospecto_desembolso_monto').val().length !== 0)
        {
            var cnfrm = confirm('Se sobrescribirá el monto registrado. ¿Confirma que quiere continuar?');
            if(cnfrm != true)
            {
                return false;
            }
        }
        
        $('#prospecto_desembolso_monto').val(monto);
    }   

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('TituloDesembCOBIS'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('SubDesembCOBIS'); ?></div>
  
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
                    if($arrRespuesta[0]["camp_id"] == 7 || $arrRespuesta[0]["camp_id"] == 8)
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
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('prospecto_jda_eval_usuario'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $this->mfunciones_generales->getNombreUsuario($arrRespuesta[0]["prospecto_jda_eval_usuario"]); ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('prospecto_jda_eval_fecha'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $this->mfunciones_generales->getFormatoFechaD_M_Y_H_M($arrRespuesta[0]["prospecto_jda_eval_fecha"]); ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('registro_num_proceso'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo ((int)$arrRespuesta[0]["registro_num_proceso"] <=0 ? $this->lang->line('prospecto_no_evaluacion') : $arrRespuesta[0]["registro_num_proceso"]); ?>
                </td>

            </tr>
            
        </table>
            
        <br />
        
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('sol_proviene'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php
                    if($sol_cred->sol_id <= 0)
                    {
                        echo "No";
                    }
                    else
                    {
                    ?>
                    
                    Si - 

                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleSolicitudCred('<?php echo $sol_cred->sol_id; ?>', 0)">
                        <?php echo 'SOL_' . $sol_cred->sol_id; ?> <i class="fa fa-external-link" aria-hidden="true"></i>
                    </span>
                    
                    <?php
                    }
                    ?>
                    
                </td>

            </tr>
            
        </table>
        
        <br />
            
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">
            
            <?php $strClase = "FilaBlanca"; ?>
            
            <?php
            if($sol_cred->sol_id > 0)
            {
            ?>
                <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                <tr class="<?php echo $strClase; ?>">
                    <td style="width: 30%; font-weight: bold;">
                        <?php echo $this->lang->line('sol_monto'); ?>
                        <span style="text-shadow: 0 0 black;" class="AyudaTooltip" data-balloon-length="medium" data-balloon="El monto que se muestra, así como la moneda, son los que fueron registrados y aprobados en la Solicitud de Crédito. Puede utilizar el valor registrado para el monto de desembolso COBIS con la opción “Seleccionar” que se encuentra al lado del valor. <?php echo ($sol_cred->sol_moneda=='usd' ? 'Se muestran 2 valores debido a que la moneda seleccionada para la Solicitud de Crédito fue dólares: el monto registrado en esta moneda y también la conversión a bolivianos con el tipo de cambio registrados en el sistema.' : ''); ?>" data-balloon-pos="right"> </span>
                    </td>

                    <td style="width: 70%; font-weight: bold;">

                        <div align="left" class="col-sm-5" <?php echo ($sol_cred->sol_moneda=='usd' ? 'style="text-align: center;"' : ''); ?>>

                            <?php echo $this->mfunciones_microcreditos->GetValorCatalogo($sol_cred->sol_moneda, 'sol_moneda') . ' ' . number_format($sol_cred->sol_monto, 2, '.', ','); ?>

                            <span class="EnlaceSimple" onclick="UseAmount(<?php echo $sol_cred->sol_monto; ?>)">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>Seleccionar 
                            </span>

                        </div>

                            <?php
                            if($sol_cred->sol_moneda == 'usd')
                            {
                                $arrConf = $this->mfunciones_logica->ObtenerDatosConf_General();
                                $this->mfunciones_generales->Verificar_ConvertirArray_Hacia_Matriz($arrConf);
                                
                            ?>

                            <div align="left" class="col-sm-5" style="text-align: center;">

                                <span style="cursor: pointer" data-balloon-length="medium" data-balloon="Este valor es la conversión de dólares a bolivianos con el tipo de cambio registrado en las configuraciones generales del sistema: <?php echo number_format($arrConf[0]['conf_credito_tipo_cambio'], 2, '.', ','); ?>." data-balloon-pos="right"> <i class="fa fa-info-circle" aria-hidden="true"></i> </span>
                                
                                <?php
                                
                                    $monto_bs = $sol_cred->sol_monto * $arrConf[0]['conf_credito_tipo_cambio'];
                                            
                                    echo $this->mfunciones_microcreditos->GetValorCatalogo('bob', 'sol_moneda') . ' ' . number_format($monto_bs, 2, '.', ',');
                                ?>

                                <span class="EnlaceSimple" onclick="UseAmount(<?php echo $monto_bs; ?>)">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>Seleccionar 
                                </span>
                                
                            </div>

                            <?php
                            }

                            echo '<div style="clear: both;"></div>';
                            ?>

                    </td>
                </tr>
            
            <?php
            }
            ?>
                
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">
                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('prospecto_desembolso_monto'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["prospecto_desembolso_monto"]; ?>
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
            <div class="PreguntaTexto "><?php echo $this->lang->line('DesembCOBIS_Pregunta'); ?></div>
        
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