<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Afiliador/ValOper/Guardar',
            'divVistaMenuPantalla', 'divErrorListaResultado');

    $("#divCargarFormulario").show();
    $("#confirmacion").hide();
    $("#TextApproveConfirm").hide();

    function MostrarConfirmación()
    {
        if($("#segip_operador_resultado").val() == "1")
        {
            $("#TextApproveConfirm").show();
        }
        
        $("#divCargarFormulario").hide();
        $("#confirmacion").fadeIn(500);
    }
    
    function OcultarConfirmación()
    {
        $("#TextApproveConfirm").hide();
        $("#divCargarFormulario").fadeIn(500);    
        $("#confirmacion").hide();
    }

    function Ajax_CargarAccion_BandejaSolicitud(estado) {
        var strParametros = "&estado=" + estado + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Afiliador/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function Visor_DocumentoTercerosAux(documento_base64, documento, tipo_aux) {
		
        var r = Math.random().toString(36).substr(2,16);
        var type = 'ter';
        window.open('Documento/VisualizarAux?' + r + '&type=' + type + '&documento_base64=' + documento_base64 + '&documento=' + documento + '&tipo_aux=' + tipo_aux, '_blank');
		
    }

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('ValOperTitulo'); ?> </div>
            <div class="FormularioSubtituloComentarioNormal "> <?php echo $this->lang->line('ValOperSubtitulo'); ?> </div>
        
        <div style="clear: both"></div>
        
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["terceros_id"])){ echo $arrRespuesta[0]["terceros_id"]; } ?>" />
            
            <input type="hidden" name="tipo_rechazo" value="4" />

            <div class="panel-heading"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Información Registrada</div>

            <div class='col-sm-4'>

                <label class="label-campo texto-centrado"> Registro del Solicitante </label> <br />
                
                <table class="tablaresultados Mayuscula" style="width: 90% !important; box-shadow: 0 4px 6px 0 rgb(0 0 0 / 20%), 0 4px 10px 0 rgb(0 0 0 / 19%);" border="0">

                    <?php $strClase = "FilaGris"; ?>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('solicitud_fecha'); ?>                    
                        </td>

                        <td style="width: 70%;">                
                            <?php echo $arrRespuesta[0]["terceros_fecha"]; ?>
                        </td>

                    </tr>

                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('ws_segip_codigo_resultado'); ?>                    
                        </td>

                        <td style="width: 70%;">                
                            <?php echo $arrRespuesta[0]["ws_segip_codigo_resultado"] . '(Cód. ' . $arrRespuesta[0]["ws_segip_codigo_resultado_codigo"] . ')'; ?>
                        </td>

                    </tr>
                    
                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('ws_cobis_codigo_resultado'); ?>                    
                        </td>

                        <td style="width: 70%;">                
                            <?php echo $arrRespuesta[0]["ws_cobis_codigo_resultado"] . '(Cód. ' . $arrRespuesta[0]["ws_cobis_codigo_resultado_codigo"] . ')'; ?>
                        </td>

                    </tr>
                    
                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('ws_selfie_codigo_resultado'); ?>                    
                        </td>

                        <td style="width: 70%;">                
                            <?php echo $arrRespuesta[0]["ws_selfie_codigo_resultado"] . '(Cód. ' . $arrRespuesta[0]["ws_selfie_codigo_resultado_codigo"] . ')'; ?>
                        </td>

                    </tr>
                    
                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <?php echo $this->lang->line('ws_ocr_codigo_resultado'); ?>                    
                        </td>

                        <td style="width: 70%;">                
                            <?php echo $arrRespuesta[0]["ws_ocr_codigo_resultado"] . '(Cód. ' . $arrRespuesta[0]["ws_ocr_codigo_resultado_codigo"] . ')'; ?>
                        </td>

                    </tr>
                    
                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <i class='fa fa-angle-double-right' aria-hidden='true'></i> <?php echo $this->lang->line('ws_ocr_name_valor'); ?>                    
                        </td>

                        <td style="width: 70%;">                
                            <?php echo $arrRespuesta[0]["ws_ocr_name_valor"]; ?>
                        </td>

                    </tr>
                    
                    <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <td style="width: 30%; font-weight: bold;">
                            <i class='fa fa-angle-double-right' aria-hidden='true'></i> <?php echo $this->lang->line('ws_ocr_name_similar'); ?>                    
                        </td>

                        <td style="width: 70%;">                
                            <?php echo $arrRespuesta[0]["ws_ocr_name_similar"]; ?>%
                        </td>

                    </tr>
                    
                    <?php //$strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 60%; font-weight: bold;'><?php echo $this->lang->line('cI_numeroraiz'); ?></td><td style='width: 40%;'><?php echo $arrRespuesta[0]['cI_numeroraiz']; ?></td></tr>
                    <?php //$strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 60%; font-weight: bold;'><?php echo $this->lang->line('cI_complemento'); ?></td><td style='width: 40%;'><?php echo $arrRespuesta[0]['cI_complemento']; ?></td></tr>
                    <?php //$strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 60%; font-weight: bold;'><?php echo $this->lang->line('cI_lugar_emisionoextension'); ?></td><td style='width: 40%;'><?php echo $arrRespuesta[0]['cI_lugar_emisionoextension']; ?></td></tr>
                    <?php //$strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 60%; font-weight: bold;'><?php echo $this->lang->line('cI_confirmacion_id'); ?></td><td style='width: 40%;'><?php echo $arrRespuesta[0]['cI_confirmacion_id']; ?></td></tr>
                    <?php //$strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 60%; font-weight: bold;'><?php echo $this->lang->line('di_fecha_vencimiento'); ?></td><td style='width: 40%;'><?php echo $arrRespuesta[0]['di_fecha_vencimiento']; ?></td></tr>
                    <?php //$strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 60%; font-weight: bold;'><?php echo $this->lang->line('di_indefinido'); ?></td><td style='width: 40%;'><?php echo $arrRespuesta[0]['di_indefinido']; ?></td></tr>
                    <?php //$strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 60%; font-weight: bold;'><?php echo $this->lang->line('di_primernombre'); ?></td><td style='width: 40%;'><?php echo $arrRespuesta[0]['di_primernombre']; ?></td></tr>
                    <?php //$strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 60%; font-weight: bold;'><?php echo $this->lang->line('di_segundo_otrosnombres'); ?></td><td style='width: 40%;'><?php echo $arrRespuesta[0]['di_segundo_otrosnombres']; ?></td></tr>
                    <?php //$strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 60%; font-weight: bold;'><?php echo $this->lang->line('di_primerapellido'); ?></td><td style='width: 40%;'><?php echo $arrRespuesta[0]['di_primerapellido']; ?></td></tr>
                    <?php //$strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 60%; font-weight: bold;'><?php echo $this->lang->line('di_segundoapellido'); ?></td><td style='width: 40%;'><?php echo $arrRespuesta[0]['di_segundoapellido']; ?></td></tr>
                    <?php //$strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 60%; font-weight: bold;'><?php echo $this->lang->line('di_fecha_nacimiento'); ?></td><td style='width: 40%;'><?php echo $arrRespuesta[0]['di_fecha_nacimiento']; ?></td></tr>
                    <?php //$strClase = $strClase == 'FilaBlanca' ? 'FilaGris' : 'FilaBlanca'; ?> <tr class='<?php echo $strClase; ?>'><td style='width: 60%; font-weight: bold;'><?php echo $this->lang->line('di_genero'); ?></td><td style='width: 40%;'><?php echo $arrRespuesta[0]['di_genero']; ?></td></tr>

                </table>

            </div>
        
        <?php
        
            $stilo_imagen = 'border: 10px solid #fff; max-height: 185px; max-width: 98%; box-shadow: 0 4px 6px 0 rgb(0 0 0 / 20%), 0 4px 10px 0 rgb(0 0 0 / 19%);';
            
            // Se captura los valores de los elementos digitalizados
            // 9=Selfie     10=CI Anveros       11=CI Reverso       20=Certificado SEGIP
        
            $arrImagenes = array(9, 10, 11); 
            
            $key_image = 20;
            $result_imagen = $this->mfunciones_generales->GetInfoTercerosDigitalizadoPDF($arrRespuesta[0]["terceros_id"], $key_image, 'documento');
            $result_nombre = $this->mfunciones_generales->GetDocumentoNombre($key_image);
        ?>
        
        <div class='col-sm-7'>
            
            <?php
                echo '<label class="label-campo texto-centrado" >' . $result_nombre . ($result_imagen == FALSE ? '' : ' - <span class="EnlaceSimple" onclick="Visor_DocumentoTercerosAux(' . $arrRespuesta[0]["terceros_id"] . ', ' . $key_image . ', ' . 1 .')"> Ver <i class="fa fa-external-link" aria-hidden="true"></i></span>') . '</label> <br />';
                
                if($result_imagen == FALSE)
                {
                    echo "<div class='TamanoContenidoGeneral'> <br /><br /> <div class='label-campo'> <i class='fa fa-meh-o' aria-hidden='true'></i> <strong> El " . $result_nombre . " no se encuentra digitalizado. La respuesta de la consulta a SEGIP no incluyó el documento en PDF.</strong> </div> </div>";
                }
                else
                {
                    $ua = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
                    if (preg_match('~MSIE|Internet Explorer~i', $ua) || (strpos($ua, 'Trident/7.0') !== false && strpos($ua, 'rv:11.0') !== false))
                    {
                        echo "<div class='TamanoContenidoGeneral'> <br /><br /> <div class='label-campo'> <i class='fa fa-meh-o' aria-hidden='true'></i> <strong> Lo sentimos, tu explorador no soporta el visor de documentos PDF.<br />Por favor ingresa con un explorador actualizado (Chrome, Firefox, Safari, Opera, etc.). <br /><br /> También puede acceder al documento directamente " . ($result_imagen == FALSE ? '' : ' - <span class="EnlaceSimple" onclick="Visor_DocumentoTercerosAux(' . $arrRespuesta[0]["terceros_id"] . ', ' . $key_image . ', ' . 1 .')"> Ver <i class="fa fa-external-link" aria-hidden="true"></i></span>') . ".</strong> </div> </div>";
                    }
                    else
                    {
                        echo '<embed style="box-shadow: 0 4px 6px 0 rgb(0 0 0 / 20%), 0 4px 10px 0 rgb(0 0 0 / 19%);" title="' . $result_nombre . '" type="application/pdf" width="100%" height="450px" src="data:application/pdf;base64,' . $result_imagen . '#toolbar=0&navpanes=0&scrollbar=0&view=FitH" />';
                    }
                }
            ?>
            
        </div>
        
        <div style="clear: both"></div>
            
        <br />
        
        <div class="panel-heading"> <i class="fa fa-address-card-o" aria-hidden="true"></i> Elementos Digitalizados </div>
        
        <?php 

        foreach ($arrImagenes as $key_image)
        {
            $result_imagen = $this->mfunciones_generales->GetInfoTercerosDigitalizado($arrRespuesta[0]["terceros_id"], $key_image, 'documento');
            $result_nombre = $this->mfunciones_generales->GetDocumentoNombre($key_image);

            echo '<div class="col-sm-4"><label class="label-campo texto-centrado" >' . $result_nombre . ' - <span class="EnlaceSimple" onclick="Visor_DocumentoTercerosAux(' . $arrRespuesta[0]["terceros_id"] . ', ' . $key_image . ', ' . $arrRespuesta[0]['tercero_asistencia'] .')"> Ver <i class="fa fa-external-link" aria-hidden="true"></i></span></label> <br /> ' . ($result_imagen == FALSE ? '<label class="label-campo" > NO REGISTRADO </label>' : '<img border="0" id="img_' . $key_image . '" style="' . $stilo_imagen . '" src="' . $result_imagen . '" title="' . $result_nombre . '" /></div>');
        }
        
        ?>
            
        <div style="clear: both"></div>
            
        <br /><br />
        
        <div class="panel-heading"> <i class="fa fa-check-square-o" aria-hidden="true"></i> Registre el Resultado de la <?php echo $this->lang->line('ValOperTitulo'); ?> </div>
        
        <br />
        
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="FilaGris">

                <td colspan="2" style="font-weight: bold; text-align: center;">
                    
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('segip_operador_resultado_form'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["segip_operador_resultado"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('segip_operador_texto_form'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["segip_operador_texto"]; ?>
                </td>

            </tr>
            
        </table>

        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarAccion_BandejaSolicitud(<?php echo $arrRespuesta[0]["terceros_estado"]; ?>);" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "> <?php echo $this->lang->line('segip_operador_pregunta'); ?> </div>
        
            <?php echo $this->mfunciones_generales->getTextConfirm(); ?>
            
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