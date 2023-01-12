<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Ejecutivo/TransfPros/Guardar',
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

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>

        <?php
        
            $tituloF = $this->lang->line('EjecutivoTitulo_prospecto_editar');
            $subtituloF = $this->lang->line('EjecutivoSubtitulo_prospecto_editar');
            $pregunta = $this->lang->line('ejecutivo_Pregunta4');
        
        ?>
        
            <div class="FormularioSubtitulo"> <?php echo $tituloF . $this->mfunciones_generales->GetValorCatalogo($_SESSION["identificador_tipo_perfil_app"], 'tipo_perfil_app') . $nombre_region; ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $subtituloF; ?></div>
        
        <div style="clear: both"></div>
                
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["ejecutivo_id"])){ echo $arrRespuesta[0]["ejecutivo_id"]; } ?>" />

            <input type="hidden" name="tipo_accion" value="<?php echo $tipo_accion; ?>" />
            
            <input type="hidden" name="visita_codigo" value="<?php echo $visita_codigo; ?>" />
            
            <input type="hidden" name="codigo_tipo_persona" value="<?php echo $codigo_tipo_persona; ?>" />
        
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

            <?php $strClase = "FilaGris"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('prospecto_id'); ?>
                </td>

                <td style="width: 70%;">
                    
                    <?php
                    
                    switch ((int)$codigo_tipo_persona) {
                        
                        // Solicitud de Crédito
                        case 6:

                            echo "SOL_" . $arrProspecto[0]["sol_id"];
                            
                            $oficial_negocio = $arrProspecto[0]["agente_nombre"];
                            
                            break;

                        // Normalizador/Cobrador
                        case 13:
                            
                            echo $this->lang->line('norm_prefijo') . $arrProspecto[0]["norm_id"];
                            
                            $oficial_negocio = $arrProspecto[0]["agente_nombre"];
                            
                            break;
                        
                        default:
                            
                            $oficial_negocio = $arrProspecto[0]["ejecutivo_asignado_nombre"];
                            
                        ?>
                            <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleProspecto('<?php echo $arrProspecto[0]["prospecto_id"]; ?>')">
                                <?php echo PREFIJO_PROSPECTO . $arrProspecto[0]["prospecto_id"]; ?>                                    
                            </span>
                        <?php
                            break;
                    }
                    
                    ?>
                    
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo ((int)$codigo_tipo_persona==13 ? 'Cliente/Caso' : 'Solicitante'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrProspecto[0]["general_solicitante"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('ejecutivo_nombre'); ?> Actual
                </td>

                <td style="width: 70%;">
                    <?php echo $oficial_negocio; ?>
                </td>

            </tr>
            
        </table>
            
        <br /><br />
            
        <table class="tablaresultados Mayuscula" style="width: 90% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('usuario_app_nombre'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php
                        echo $arrParent;
                    ?>
                </td>

            </tr>
        </table>
        
        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarAccion_Prospecto('<?php echo $arrRespuesta[0]["ejecutivo_id"]; ?>');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>
        
        

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $pregunta ?></div>
        
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