<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Region/Asignar/Guardar',
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
    
    function MarcarOpcionesFiltroLista(){
        var opciones = $('#campoFiltroLista').find("input[type=checkbox]");
        var opcionesMarcadas = $('#campoFiltroLista').find("input[type=checkbox]:checked");
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
    
    function MarcarOpcionesFiltroDep(clase){
        
        var opciones = $("input."+clase+":checkbox");
        var opcionesMarcadas = $("input."+clase+":checkbox:checked");
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

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('RegionalizaTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('RegionalizaSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
                
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["usuario_codigo"])){ echo $arrRespuesta[0]["usuario_codigo"]; } ?>" />

            <input type="hidden" name="tipo_accion" value="<?php echo $tipo_accion; ?>" />
        
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

            <?php $strClase = "FilaGris"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('Usuario_user'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["usuario_user"]; ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">
                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('Usuario_nombre'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["usuario_nombre_completo"]; ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">
                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('Usuario_rol'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["usuario_rol"]; ?>
                </td>
            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">
                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('estructura_regional'); ?> asignada por defecto
                </td>

                <td style="width: 70%; font-weight: bold;">                
                    <?php echo $arrRespuesta[0]["usuario_region"]; ?>
                </td>
            </tr>

        </table>

        <br />
            
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td valign="top" style="width: 30%; font-weight: bold; text-align: center;">
                    
                    <br /><br />
                    
                    <?php echo $this->lang->line('regionaliza_seleccion'); ?>
                    
                    <br /><br />
                    
                    <a onclick="MarcarOpcionesFiltroLista()" id="FiltroReporteFilaListaBotonMarcar" style="font-weight: bold;"><i class="fa fa-object-group" aria-hidden="true"></i> Marcar/Desmarcar todos</a>
                    
                </td>

                <td style="width: 70%;" id="campoFiltroLista">                        
                    
                    <br />

                    <?php

                        if(isset($arrRegion[0]))
                        {
                            $i = 0;
                            $checked = '';

                            $arrRegion = $this->mfunciones_generales->ArrGroupBy($arrRegion, 'estructura_regional_departamento');
                            
                            foreach ($arrRegion as $key => $values)
                            {
                                $aux_clase = 'dep_' . str_replace(' ', '', strtolower($key));
                                
                                echo '<div style="clear: both;"><div style="font-style: italic; font-weight: bold; text-align: center;"> <i class="fa fa-map-marker" aria-hidden="true"></i> ' . $key . '<br /><a style="font-weight: bold; font-size: 0.8em;" class="' . $aux_clase . '" onclick="MarcarOpcionesFiltroDep(\'' . $aux_clase . '\');"><i class="fa fa-object-group" aria-hidden="true"></i> Marcar/Desmarcar</a> </div><div style="clear: both;"></div>';
                                
                                foreach ($values as $value)
                                {
                                    $checked = '';
                                    if($value["region_asignado"] || $value["estructura_regional_nombre"] === $arrRespuesta[0]["usuario_region"])
                                    {
                                        $checked = 'checked="checked"';
                                    }

                                    echo '<div class="divOpciones">';
                                    echo '<input class="' . $aux_clase . '" id="region' . $i , '" type="checkbox" name="region_list[]" '. $checked .' value="' . $value["estructura_regional_id"] . '">';
                                    echo '<label for="region' . $i , '"><span></span>' . $value["estructura_regional_nombre"] . '</label>';
                                    echo '<br /><br /></div>';

                                    $i++;
                                }
                                
                            }
                        }

                    ?>

                </td>

            </tr>
            
        </table>

        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Region/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
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