<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Bandeja/Antecedentes/Guardar',
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
    
    function Ajax_CargarAccion_DetalleEmpresa(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Empresa/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('AntecedentesTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('AntecedentesSubtitulo'); ?></div>
        
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
                    <br />
                    <?php echo $this->lang->line('prospecto_nombre_empresa'); ?> Empresa
                    <br /><br />
                </td>

                <td style="width: 70%; font-weight: bold;">
                    <br />
                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleEmpresa('<?php echo $arrRespuesta[0]["empresa_id"]; ?>')">
                    <?php echo $arrRespuesta[0]["empresa_nombre"]; ?>
                    </span>
                    <br /><br />
                </td>

            </tr>
            
        </table>
        
        <br />
        
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('antecedentes_pep'); ?>
                </td>

                <td style="width: 70%;">
                    
                    <input id="pep1" name="pep" type="radio" class="" value="1" />
                    <label for="pep1" class=""><span></span>Si</label>

                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                    <input id="pep2" name="pep" type="radio" class="" checked="checked" value="0" />
                    <label for="pep2" class=""><span></span>No</label>
                
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('antecedentes_match'); ?>
                </td>

                <td style="width: 70%;">
                    
                    <input id="match1" name="match" type="radio" class="" value="1" />
                    <label for="match1" class=""><span></span>Si</label>

                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                    <input id="match2" name="match" type="radio" class="" checked="checked" value="0" />
                    <label for="match2" class=""><span></span>No</label>
                
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('antecedentes_infocred'); ?>
                </td>

                <td style="width: 70%;">
                    
                    <input id="infocred1" name="infocred" type="radio" class="" value="1" />
                    <label for="infocred1" class=""><span></span>Si</label>

                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                    <input id="infocred2" name="infocred" type="radio" class="" checked="checked" value="0" />
                    <label for="infocred2" class=""><span></span>No</label>
                
                </td>

            </tr>
            
        </table>
        
        <br />
        
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('antecedentes_detalle'); ?>
                </td>

                <td style="width: 70%;">
                    
                    <?php echo $arrCajasHTML["antecedentes_detalle"]; ?>
                
                </td>

            </tr>
            
        </table>
        
        <br />
        
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('antecedentes_resultado'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon='<?php echo $this->lang->line('Ayuda_antecedentes_recomendacion'); ?>' data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">
                    
                    <?php echo $arrCajasHTML["antecedentes_resultado"]; ?>
                
                </td>

            </tr>
            
        </table>
        
        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Bandeja/Preafiliacion/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('antecedentes_Pregunta'); ?></div>
        
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