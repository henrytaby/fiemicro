<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Bandeja/Cumplimiento/Guardar',
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

    function Ajax_CargarAccion_DetalleUsuario(tipo_codigo, codigo_usuario) {
        var strParametros = "&tipo_codigo=" + tipo_codigo + "&codigo_usuario=" + codigo_usuario;
        Ajax_CargadoGeneralPagina('Usuario/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('CumplimientoTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('CumplimientoSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
                
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["prospecto_id"])){ echo $arrRespuesta[0]["prospecto_id"]; } ?>" />
            
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

            <?php $strClase = "FilaGris"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('prospecto_id'); ?>
                </td>

                <td style="width: 70%;">
                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleProspecto('<?php echo $arrRespuesta[0]["prospecto_id"]; ?>')">
                        <?php echo PREFIJO_PROSPECTO . $arrRespuesta[0]["prospecto_id"]; ?>                                    
                    </span>
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('prospecto_nombre_empresa'); ?> Empresa
                </td>

                <td style="width: 70%;">
                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleEmpresa('<?php echo $arrRespuesta[0]["empresa_id"]; ?>')">
                    <?php echo $arrRespuesta[0]["empresa_nombre"]; ?>
                    </span>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('empresa_categoria_detalle'); ?>
                </td>

                <td style="width: 70%;">
                    <?php echo $arrRespuesta[0]["empresa_categoria"]; ?>
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('ejecutivo_asignado_nombre'); ?>
                </td>

                <td style="width: 70%;">
                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleUsuario(0, '<?php echo $arrRespuesta[0]["usuario_id"]; ?>')">
                        <?php echo $arrRespuesta[0]["ejecutivo_asignado_nombre"]; ?>
                    </span>                    
                </td>
            </tr>
            
        </table>
        
        <br />
        
        <table class="tblListas Centrado" style="width: 80%;" border="0">
                
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="FilaCabecera">
                <th style="width:30%;">
                    <strong><?php echo $this->lang->line('requisitos_titulo_opcion'); ?></strong>
                </td>

                <th style="width:50%;">
                    <strong><?php echo $this->lang->line('requisitos_titulo_requisito'); ?></strong>
                </td>

                <th style="width:20%;">
                    
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="FilaBlanca">
                <td style="text-align: justify;">
                    <strong><?php echo $this->lang->line('cumplimiento_opcion_vobo'); ?></strong>
                </td>

                <td style="text-align: justify;">
                    <?php echo $this->lang->line('cumplimiento_opcion_vobo_des'); ?>
                </td>

                <td style="text-align: center;">
                    <input id="seleccion1" name="opcion_seleccion" type="radio" class="" value="1" />
                    <label for="seleccion1" class=""><span></span>Seleccionar</label>
                </td>
            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="FilaBlanca">
                <td style="text-align: justify;">
                    <strong><?php echo $this->lang->line('cumplimiento_opcion_rechazar'); ?></strong>
                </td>

                <td style="text-align: justify;">
                    <?php echo $this->lang->line('cumplimiento_opcion_rechazar_des'); ?>
                </td>

                <td style="text-align: center;">
                    <input id="seleccion2" name="opcion_seleccion" type="radio" class="" value="2" />
                    <label for="seleccion2" class=""><span></span>Seleccionar</label>
                </td>
            </tr>

        </table>
        
        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Bandeja/Cumplimiento/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('cumplimiento_Pregunta'); ?></div>
        
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