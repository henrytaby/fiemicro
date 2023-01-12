<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Interno/Mantenimiento/Guardar',
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
    
    $("#fila_otro").addClass("ocultar_elemento");
    
    function HideOtro(){
        //document.getElementById('fila_categoria').style.display = 'none';
        $("#fila_otro").addClass("ocultar_elemento");
    }
    
    function ShowOtro(){
        //document.getElementById('fila_categoria').style.display = 'block';
        $("#fila_otro").removeClass("ocultar_elemento");
    }

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('NuevoMantenimientoTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('MantenimientoSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0])){ echo $arrRespuesta[0]["solicitud_id"]; } ?>" />

            <input type="hidden" name="tipo_accion" value="<?php echo $tipo_accion; ?>" />
            
        <table class="tablaresultados Mayuscula" style="width: 100% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_nit'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["solicitud_nit"]; ?>
                </td>
            </tr>
            
            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_nombre_empresa'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["solicitud_nombre_empresa"]; ?>
                </td>
            </tr>

            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">
                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('solicitud_email'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["solicitud_email"]; ?>
                </td>
            </tr>
            
            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('mantenimiento_tareas'); ?>
                </td>

                <td style="width: 70%;">

                    <br />
                        
                    <?php

                        if(isset($arrTareas[0]))
                        {
                            $i = 0;
                            $checked = '';

                            foreach ($arrTareas as $key => $value) 
                            {
                                echo '<div class="divOpciones">';
                                echo '<input id="tarea' . $i , '" type="checkbox" name="tarea_list[]" '. $checked .' value="' . $value["tarea_id"] . '">';
                                echo '<label for="tarea' . $i , '"><span></span>' . $value["tarea_detalle"] . '</label>';
                                echo '</div>';

                                $i++;
                            }
                        }

                    ?>
                </td>

            </tr>
            
            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">
                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('mantenimiento_otro_elegir'); ?>
                </td>

                <td style="width: 70%;">
                    
                    <input id="otro1" name="otro" type="radio" class="" checked='checked' value="0" onclick="HideOtro();" />
                    <label for="otro1" class=""><span></span>No</label>
                    
                    &nbsp;
                    
                    <input id="otro2" name="otro" type="radio" class="" value="1" onclick="ShowOtro();" />
                    <label for="otro2" class=""><span></span>Si</label>
                    
                </td>
            </tr>
            
            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>" id="fila_otro">
                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('mantenimiento_otro'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrCajasHTML["solicitud_otro_detalle"]; ?>
                </td>
            </tr>
            
        </table>

        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Solicitud/Menu');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('editar_solicitud_Pregunta2'); ?></div>
        
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