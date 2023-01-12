<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    Elementos_Habilitar_ObjetoARefComoSubmit("btnGuardarDatosLista", "FormularioRegistroLista");
    Ajax_DarActualizarValidacionEnvioAjaxSegmentoForm("FormularioRegistroLista", 'Afiliador/Agencia/CuentaCerradaGuardar',
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
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('TituloCuentaCerrada') . $nombre_region; ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('SubCuentaCerrada'); ?></div>
  
        <div style="clear: both"></div>
        
        <br />

        <form id="FormularioRegistroLista" method="post">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["terceros_id"])){ echo $arrRespuesta[0]["terceros_id"]; } ?>" />
            
            <input type="hidden" name="tipo_rechazo" value="4" />

            
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    Nombre del Solicitante
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["nombre_persona"]; ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    Correo Electrónico
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["email"]; ?>
                </td>

            </tr>
            
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
                    <?php echo $this->lang->line('tipo_cuenta'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["tipo_cuenta"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('onboarding_numero_cuenta'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrRespuesta[0]["onboarding_numero_cuenta"]; ?>
                </td>

            </tr>
            
            <?php $strClase = "FilaGris"; ?>
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('causal_cuenta_cerrada'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $this->mfunciones_generales->ObtenerCatalogoSelect('cuenta_cerrada_causa', '', 'causa_cuenta_cerrada'); ?>
                </td>

            </tr>
            
            <?php $strClase = "FilaGris"; ?>
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('notificar_cuenta_cerrada'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["cuenta_cerrada_envia"]; ?>
                </td>

            </tr>
            
        </table>

        </form>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            
            <?php
            
                $direccion_bandeja = 'Menu/Principal';
            
                if(isset($_SESSION['direccion_bandeja_actual']))
                {
                    $direccion_bandeja = $_SESSION['direccion_bandeja_actual'];
                }
            
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
            <div class="PreguntaTexto "><?php echo $this->lang->line('cuenta_cerrada_Pregunta'); ?></div>
        
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