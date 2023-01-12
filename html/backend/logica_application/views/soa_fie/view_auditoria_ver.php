<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'AuditoriaSOA<?php echo ($accion_gestion=='borrado' ? 'clear' : '') ;?>/Resultado',
            'divResultadoReporte', 'divErrorListaResultado', 'SLASH');

    $(document).ready(function(){
        $("#fecha_inicio").datepicker({changeYear: true, changeMonth: true, onSelect: function(selected) {
              $("#fecha_fin").datepicker("option","minDate", selected);
        }});
        $("#fecha_fin").datepicker({changeYear: true, changeMonth: true});
    });

	function LimpiarFechas() {
        $("#fecha_inicio").val('');
        $("#fecha_fin").val('');
    }
    
    <?php
    if($accion_gestion == 'borrado')
    {
    ?>
        $("#divCargarFormulario").show();
        $("#confirmacion").hide();

        function MostrarConfirmación()
        {
            $("#divCargarFormulario").hide();
            $("#confirmacion").fadeIn(500);
        }

        function OcultarConfirmación()
        {
            $("html, body").animate({ scrollTop: $(document).height() }, 1);
            $("#divCargarFormulario").fadeIn(500);    
            $("#confirmacion").hide();
        }
        
        function Ajax_CargarAccion_SOAGenerateLogsCSV() {
            window.open('AuditoriaSOAclear/ExportaCSV');return false;
        }

    <?php
    }
    ?>

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> Auditoría SOA-FIE <?php echo ($accion_gestion=='borrado' ? 'Borrado' : 'Reporte') ;?> </div>
            <div class="FormularioSubtituloComentarioNormal "> <?php echo ($accion_gestion=='borrado' ? 'En este apartado podrá borrar los registros de los logs de auditoría del consumo de los web services SOA-FIE utilizados para la autenticación robusta del front-end de Onboarding Digital, respecto a los existentes en la base de datos considerando también los consumos de "token" y "refresh_token", que si bien no se muestran en el reporte, también serán borrados todos aquellos que estén dentro del rango de fechas que se seleccione. Puede ver el detalle de un registro log específico con la opción <i class="fa fa-eye" aria-hidden="true"></i><br /><br />Primeramente, seleccione un rango de fechas para generar el reporte y exporte los resultados a fin de resguardar la información antes de proceder con el borrado.<br /><br />Acto seguido, seleccione la opción de "Borrar Registros" y confirme la acción para proceder. Se registrará el usuario y fecha de la acción de borrado.' : 'En este apartado podrá generar reportes de los logs de auditoría del consumo de los web services SOA-FIE utilizados para la autenticación robusta del front-end de Onboarding Digital (respecto a los existentes en la base de datos <i>sin contar los consumos de "token" y "refresh_token"</i>). Podrá utilizar diferentes filtros para generar el reporte y exportar los resultados.<br /><br />Puede ver el detalle de un registro log específico con la opción <i class="fa fa-eye" aria-hidden="true"></i>.') ;?></div>
        
        <div style="clear: both"></div>
        
        <br />
        
		<form id="FormularioRegistroLista" method="post">
		
                    <input type="hidden" id="tipo" name="tipo" value="tabla" />
                    <input type="hidden" id="accion_gestion" name="accion_gestion" value="<?php echo $accion_gestion; ?>" />
                    
                    <?php
                    if($accion_gestion == 'borrado')
                    {
                    ?>
                    
                    <div style="text-align: right; width: 90% !important;">
                        <span title="Descargue en formato CSV los registros de acciones de borrado de logs SOA-FIE" class="EnlaceSimple" style="font-weight: bold;" onclick="Ajax_CargarAccion_SOAGenerateLogsCSV();"> <i class="fa fa-download" aria-hidden="true"></i> CSV Logs registros borrados </span>
                    </div>
                    
                    <?php
                    }
                    ?>
                    
                    <table class="tablaresultados Mayuscula" style="width: 90% !important;" border="0">

                        <?php $strClase = "FilaGris"; ?>
                        <tr class="<?php echo $strClase; ?>">

                            <td style="width: 15%; text-align: right;">
                                <span> <strong> Rango de Fechas: </strong> </span>
                                <a onclick="LimpiarFechas();" title="Limpiar Fechas" >
                                    <img src="html_public/imagenes/limpiar_pequeno.png" style="vertical-align: bottom; height: 20px;" />
                                </a>
                            </td>

                            <td style="width: 85%;" <?php echo ($accion_gestion=='seguimiento' ? 'colspan="2"' : ''); ?>>                

                                <table style="width: 100%; border: 0px;">
                                    <tr>
                                        <td style="width: 50%;" valign="bottom">
                                            <strong> Del: <?php echo $arrCajasHTML["fecha_inicio"]; ?> </strong>
                                        </td>
                                        <td style="width: 50%;" valign="bottom">
                                                <strong> Al: <?php echo $arrCajasHTML["fecha_fin"]; ?> </strong>
                                        </td>
                                    </tr>                            
                                </table>

                            </td>

                        </tr>
                        
                        <?php
                        if($accion_gestion == 'seguimiento')
                        {
                        ?>
                            <tr class="<?php echo $strClase; ?>">

                                <td style="width: 34%;">
                                    <strong> Por Servicio: </strong>
                                    <br />
                                    <?php

                                    $arrListadoServicio = Array(
                                        array("id" => "-1", "campoDescrip" => "Todos"),
                                        array("id" => "1", "campoDescrip" => "Validación COBIS-SEGIP"),
                                        array("id" => "2", "campoDescrip" => "Prueba de vida"),
                                        array("id" => "3", "campoDescrip" => "OCR")
                                    );

                                    echo html_select('servicio', $arrListadoServicio, 'id', 'campoDescrip', 'SINSELECCIONAR', '');

                                    ?>

                                </td>

                                <td style="width: 33%;">
                                    <strong> Tipo de Respuesta: </strong>
                                    <br />
                                    <?php

                                    $arrListadoRespuesta = Array(
                                        array("id" => "-1", "campoDescrip" => "Todos"),
                                        array("id" => "1", "campoDescrip" => "Exitoso"),
                                        array("id" => "2", "campoDescrip" => "Error")
                                    );

                                    echo html_select('respuesta', $arrListadoRespuesta, 'id', 'campoDescrip', 'SINSELECCIONAR', '');

                                    ?>

                                </td>

                                <td style="width: 33%;">
                                    <strong> Tipo de Validación: </strong>
                                    <br />
                                    <?php

                                    $arrListadoRespuesta = Array(
                                        array("id" => "-1", "campoDescrip" => "Todos"),
                                        array("id" => "1", "campoDescrip" => "Primer intento"),
                                        array("id" => "2", "campoDescrip" => "Reintento")
                                    );

                                    echo html_select('validacion', $arrListadoRespuesta, 'id', 'campoDescrip', 'SINSELECCIONAR', '');

                                    ?>

                                </td>

                            </tr>

                        <?php
                        }
                        ?>
                            
                    </table>
		
		</form>
        
	<br />
        <div>
            <a id="btnGuardarDatosLista" class="BotonMinimalista"> <i class="fa fa-search" aria-hidden="true"></i> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
		
        <div style="clear: both"></div>
        
        <div id="divErrorListaResultado" class="mensajeBD"> </div>
		
        <div id="divResultadoReporte" style="width: 100%;"> </div>
        
        <br /><br /><br /><br /><br /><br />

    </div>
    
    <?php
    if($accion_gestion == 'borrado')
    {
    ?>
        <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

            <div class="FormularioSubtituloImagenPregunta"> </div>

                <div class="PreguntaTitulo"> CONFIRMAR ANTES DE PROCEDER </div>
                <div class="PreguntaTexto ">
                    ¿El rango de fechas de borrado de registros es correcto y se resguardó previamente la información antes de proceder con el borrado de registros?
                </div>
                
                <div style="clear: both"></div>

                <div style="text-align: center;">
                    <input id="seleccion-no" name="opcion_seleccion" type="radio" class="" checked="checked" value="0" />
                    <label style="font-size: 1.5em;" for="seleccion-no" class=""><span></span>No</label>

                    &nbsp;&nbsp;

                    <input id="seleccion-si" name="opcion_seleccion" type="radio" class="" value="1" />
                    <label style="font-size: 1.5em;" for="seleccion-si" class=""><span></span>Si</label>
                </div>
                
                <br />

            <div class="Botones2Opciones">
                <a onclick="OcultarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
            </div>

            <div class="Botones2Opciones">
                <a class="BotonMinimalista" onclick="Ajax_CargarAccion_Borrado_Log();"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
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

            <div id="divErrorResultado" class="mensajeBD"> </div>

        </div>
    <?php
    }
    ?>
</div>