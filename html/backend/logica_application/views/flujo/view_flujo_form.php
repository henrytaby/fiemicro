<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Flujo/Guardar',
            'divVistaMenuPantalla', 'divErrorListaResultado');

    $("#divCargarFormulario").show();    
    $("#confirmacion").hide();

    function MostrarConfirmación()
    {
        $("#divCargarFormulario").hide();
        $("#confirmacion").fadeIn(500);
    }
    
    function OcultarConfirmación()
    {
        $("#divCargarFormulario").fadeIn(500);    
        $("#confirmacion").hide();
    }
    
    $('#codigo_parent_old').prop('disabled', 'disabled');
    
    function Ajax_CargarAccion_AsignarActores(codigo_etapa) {
        var strParametros = "&codigo_etapa=" + codigo_etapa;
        Ajax_CargadoGeneralPagina('Flujo/Asignar/Ver', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function divConf()
    {
        if($("[name=alertar]:checked").val() == 1)
        {
            $("#conf_alerta").removeClass("ui-helper-hidden");
        }
        else
        {
            $("#conf_alerta").addClass("ui-helper-hidden");
        }
    }
    
    function MarcarOpcionesFiltroLista(){
        var opciones = $('#conf_alerta').find("input[type=checkbox]");
        var opcionesMarcadas = $('#conf_alerta').find("input[type=checkbox]:checked");
        if (opciones.length == opcionesMarcadas.length) {
            // Desmarcar
            opciones.prop("checked",false);
            opciones.attr("checked",false);
        } else
        {
            // Marcar
            opciones.prop("checked",true);
            opciones.attr("checked",true);
        }
    }
    
    <?php
        // Se pregunta si está marcada la opción de alerta correo para mostrar el div
        if($arrRespuesta[0]["etapa_alerta_correo"] == 1)
        {
            echo '$("#conf_alerta").removeClass("ui-helper-hidden");';
        }
        else
        {
            echo '$("#conf_alerta").addClass("ui-helper-hidden");';
        }
        
        if($arrRespuesta[0]["etapa_id"] == 13)
        {
            echo '$("#notificar_correo").addClass("ui-helper-hidden");';
        }
        else
        {
            echo '$("#notificar_correo").removeClass("ui-helper-hidden");';
        }
        
    ?>

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"><?php if($arrRespuesta[0]["etapa_id"] == 13 || $arrRespuesta[0]["etapa_id"] == 16) { echo "Notificación Alertas"; } else { echo $this->lang->line('FlujoTitulo'); } ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php if($arrRespuesta[0]["etapa_id"] == 13 || $arrRespuesta[0]["etapa_id"] == 16) { echo "En este apartado puede visualizar el detalle del registro seleccionado y Asignar y/o ver a los Actores (Roles y/o Usuarios) que serán notificados cuando estén Atrasados. La periodicidad de notificación será la configurada por el administrador del sistema."; } else { echo $this->lang->line('FlujoSubtitulo'); } ?></div>
        
        <div style="clear: both"></div>
        
        <?php echo "<div class='mensaje_advertencia'> " . $this->lang->line('flujo_advertencia') . " </div> <br />"; ?>
        
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["etapa_id"])){ echo $arrRespuesta[0]["etapa_id"]; } ?>" />

        <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="FilaGris">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('etapa_parent'); ?>
                </td>

                <td style="width: 70%;">
                    
                    <?php
                    
                        if($arrRespuesta[0]["etapa_id"] == 1 || $arrRespuesta[0]["etapa_id"] == 7 || $arrRespuesta[0]["etapa_id"] == 0)
                        {
                            echo '<input type="hidden" name="codigo_parent" value="' . $arrRespuesta[0]['etapa_depende'] . '" />';
                            echo '<strong>Ninguno (Inicio del Flujo)</strong>';
                        }
                        else
                        {

                            $codigo_parent = "";

                            if(isset($arrRespuesta[0]["etapa_depende"]))
                            { 
                                $codigo_parent = $arrRespuesta[0]["etapa_depende"];
                            }

                            if(isset($arrParent[0]))
                            {
                                echo html_select('codigo_parent_old', $arrParent, 'etapa_id', 'etapa_nombre', '', $codigo_parent);
                            }

                            // Cambiar si es necesario
                            echo '<input type="hidden" name="codigo_parent" value="' . $arrRespuesta[0]['etapa_depende'] . '" />';
                        }
                    ?>
                    
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('etapa_nombre'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["etapa_nombre"]; ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('etapa_detalle'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["etapa_detalle"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('etapa_tiempo'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon='<?php echo $this->lang->line('Ayuda_etapa_tiempo'); ?>' data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">
                    
                    <?php
                    
                    if($arrRespuesta[0]["etapa_id"] == 13)
                    {
                        echo "<i>No corresponde, se calculará automáticamente el tiempo total del flujo de afiliación. Actualmente son <strong>" . $this->mfunciones_generales->GetTotalHorasFlujo() . " hrs. laborales.<strong></i>";
                    }
                    
                    if($arrRespuesta[0]["etapa_id"] == 16)
                    {
                        echo "<i>Para esta alerta los días son calendario. Para convertir de horas a días multiplique por 24.<strong></i>";
                    }
                    
                    ?>
                    
                    <?php echo $arrCajasHTML["etapa_tiempo"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>" id="notificar_correo">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('etapa_notificar_correo'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon='<?php echo $this->lang->line('Ayuda_etapa_envio'); ?>' data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">                
                    
                    <br />
                    
                    <input id="notificar1" name="notificar" type="radio" class="" <?php if($arrRespuesta[0]["etapa_notificar_correo"]==0) echo "checked='checked'"; ?> value="0" />
                    <label for="notificar1" class=""><span></span>No</label>

                    &nbsp;&nbsp;

                    <input id="notificar2" name="notificar" type="radio" class="" <?php if($arrRespuesta[0]["etapa_notificar_correo"]==1) echo "checked='checked'"; ?> value="1" />
                    <label for="notificar2" class=""><span></span>Si</label>
                    
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    Color de la Etapa
                </td>

                <td style="width: 70%;">
                    
                    <?php
                    
                        $etapa_color = $arrRespuesta[0]["etapa_color"];
                        
                        if($etapa_color == NULL || $etapa_color == "")
                        {
                            $etapa_color = "#006699";
                        }
                    
                    ?>
                    
                    <input type="color" autocomplete="off" id="etapa_color" name="etapa_color" value="<?php echo $etapa_color; ?>" maxlength="40" title="" onkeydown="return (event.keyCode != 13);">
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>" <?php echo ($arrRespuesta[0]["etapa_id"] == 13 || $arrRespuesta[0]["etapa_categoria"] == 5 || $arrRespuesta[0]["etapa_id"] == 16) ? '' : 'style="display: none;"'; ?>>

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('etapa_alerta_correo'); ?>
                </td>

                <td style="width: 70%;">                
                    
                    <br />
                    
                    <input id="alerta1" name="alertar" type="radio" class="" <?php if($arrRespuesta[0]["etapa_alerta_correo"]==0) echo "checked='checked'"; ?> onchange="divConf()" value="0" />
                    <label for="alerta1" class=""><span></span>No</label>

                    &nbsp;&nbsp;

                    <input id="alerta2" name="alertar" type="radio" class="" <?php if($arrRespuesta[0]["etapa_alerta_correo"]==1) echo "checked='checked'"; ?> onchange="divConf()" value="1" />
                    <label for="alerta2" class=""><span></span>Si</label>
                    
                    <div id="conf_alerta" style="padding: 0px 20px;">
                        
                        <br />
                        
                        <?php echo $this->lang->line('etapa_alerta_ayuda'); ?>
                        
                        <br />
                        
                        <?php echo $arrCajasHTML["etapa_alerta_hora"]; ?>
                        
                        <br /><br />
                        
                        <?php $seleccion = ''; if (in_array("1", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                        <div class="divOpciones">
                        <input id="d1" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="1">
                        <label for="d1"><span></span>Lunes</label>
                        </div>

                        <?php $seleccion = ''; if (in_array("2", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                        <div class="divOpciones">
                        <input id="d2" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="2">
                        <label for="d2"><span></span>Martes</label>
                        </div>

                        <?php $seleccion = ''; if (in_array("3", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                        <div class="divOpciones">
                        <input id="d3" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="3">
                        <label for="d3"><span></span>Miércoles</label>
                        </div>

                        <?php $seleccion = ''; if (in_array("4", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                        <div class="divOpciones">
                        <input id="d4" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="4">
                        <label for="d4"><span></span>Jueves</label>
                        </div>

                        <?php $seleccion = ''; if (in_array("5", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                        <div class="divOpciones">
                        <input id="d5" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="5">
                        <label for="d5"><span></span>Viernes</label>
                        </div>

                        <?php $seleccion = ''; if (in_array("6", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                        <div class="divOpciones">
                        <input id="d6" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="6">
                        <label for="d6"><span></span>Sábado</label>
                        </div>

                        <?php $seleccion = ''; if (in_array("7", $arrDias)){ $seleccion = 'checked="checked"'; } ?>
                        <div class="divOpciones">
                        <input id="d7" type="checkbox" name="dias_list[]" <?php echo $seleccion; ?> value="7">
                        <label for="d7"><span></span>Domingo</label>
                        </div>
                        
                        <div style="clear: both"></div>
                        
                        <div style="text-align: right;">
                            <a onclick="MarcarOpcionesFiltroLista()" id="FiltroReporteFilaListaBotonMarcar" style="font-weight: bold;"><i class="fa fa-object-group" aria-hidden="true"></i> Marcar/Desmarcar todos</a>
                        </div>
                        
                    </div>
                    
                </td>

            </tr>
            
            
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="FilaGris">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('etapa_actores'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon='<?php echo $this->lang->line('Ayuda_etapa_actores'); ?>' data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">                
                    
                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_AsignarActores('<?php if(isset($arrRespuesta[0]["etapa_id"])){ echo $arrRespuesta[0]["etapa_id"]; } ?>')">
                        <strong> <i class='fa fa-users' aria-hidden='true'></i> Asignar/Ver Actores </strong> 
                    </span>
                    
                    <span id="spanActoresActualizado" style="display: none; font-style: italic;">
                        <i class='fa fa-cogs' aria-hidden='true'></i> Recientemente actualizado.
                    </span>
                    
                </td>

            </tr>
            
        </table>

        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Flujo/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>
        
        

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('estructura_Pregunta'); ?></div>
        
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


<?php
                    
if($arrRespuesta[0]["etapa_id"] == 13)
{
    echo '<script type="text/javascript"> document.getElementById("etapa_tiempo").style.display = "none"; </script>';
}

?>