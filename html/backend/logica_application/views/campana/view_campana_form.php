<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Campana/Guardar',
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
    
    $("#camp_fecha_inicio").datepicker({changeYear: true, changeMonth: true});

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('CampanaFormTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('CampanaFormSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
                
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["camp_id"])){ echo $arrRespuesta[0]["camp_id"]; } ?>" />

            <input type="hidden" name="tipo_accion" value="<?php echo $tipo_accion; ?>" />
            
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('campana_tipo'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php                                            
                        if(count($arrTipoCampana[0]) > 0)
                        {
                            $valor_parent = '-1';
                            if($tipo_accion == 1)
                            {
                                $valor_parent = $arrRespuesta[0]['camtip_id'];
                            }
                            
                            echo html_select('camtip_id', $arrTipoCampana, 'camtip_id', 'camtip_nombre', 'SINSELECCIONAR', $valor_parent);
                        }
                        else
                        {
                            echo $this->lang->line('TablaNoRegistros');
                        }
                    ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('campana_nombre'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["camp_nombre"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('campana_desc'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["camp_desc"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('campana_monto_oferta'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["camp_monto_oferta"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('campana_tasa'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["camp_tasa"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('campana_fecha_inicio'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["camp_fecha_inicio"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('campana_plazo'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["camp_plazo"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    Color del Rubro
                </td>

                <td style="width: 70%;">
                    
                    <?php
                    
                        $camp_color = (isset($arrRespuesta[0]["camp_color"])?$arrRespuesta[0]["camp_color"]:'');
                        
                        if($camp_color == NULL || $camp_color == "")
                        {
                            $camp_color = "#006699";
                        }
                    
                    ?>
                    
                    <input type="color" autocomplete="off" id="camp_color" name="camp_color" value="<?php echo $camp_color; ?>" maxlength="40" title="" onkeydown="return (event.keyCode != 13);">
                </td>

            </tr>
            
            <?php // $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?>
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('campana_servicios'); ?>
                </td>

                <td style="width: 70%;">

                    <br />
                        
                    <?php

                        if(isset($arrServicios[0]))
                        {
                            $i = 0;
                            $checked = '';

                            foreach ($arrServicios as $key => $value) 
                            {
                                $checked = '';
                                if($value["servicio_asignado"])
                                {
                                    $checked = 'checked="checked"';
                                }

                                echo '<div class="divOpciones">';
                                echo '<input id="servicio' . $i , '" type="checkbox" name="servicio_list[]" '. $checked .' value="' . $value["servicio_id"] . '">';
                                echo '<label for="servicio' . $i , '"><span></span>' . $value["servicio_detalle"] . '</label>';
                                echo '</div>';

                                $i++;
                            }
                        }

                    ?>
                </td>

            </tr>
            
        </table>

        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Campana/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>
        
        

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('campana_pregunta'); ?></div>
        
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