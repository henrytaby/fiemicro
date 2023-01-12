<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>

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
    
    $(document).ready(function () {
        $('input[type=file]').change(function () {
            var val = $(this).val().toLowerCase();
            var regex = new RegExp("(.*?)\.(pdf)$");
            if(!(regex.test(val))) 
            {
                $(this).val('');                
                alert('Archivo no válido, por favor seleccione un documento PDF.');
                
                $("#MensajeArchivo").fadeOut(150, function() {
                    $(this).addClass("ocultar_elemento");
                });
            }
            else
            {
                $("#MensajeArchivo").fadeIn(150, function() {
                    $(this).removeClass("ocultar_elemento");
                });
            }
        });    
    });
	

</script>

<div id="divVistaMenuPantalla" align="center">

    <form action="Documento/Guardar" method="post" accept-charset="utf-8" enctype="multipart/form-data" name="frm1" id="frm1">
    
    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('DocumentoTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('DocumentoSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
                
        <br />

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="estructura_id" value="<?php if(isset($arrRespuesta[0]["documento_id"])){ echo $arrRespuesta[0]["documento_id"]; } ?>" />

            <input type="hidden" name="tipo_accion" value="<?php echo $tipo_accion; ?>" />
            
            <?php
            if(isset($arrRespuesta[0]["documento_enviar_codigo"]) && $arrRespuesta[0]["documento_enviar_codigo"] == 1 && $arrRespuesta[0]["documento_enlace"] != false)
            {
                echo '<input type="hidden" name="existe_adjunto" value="1" />';
            }
            else
            {
                echo '<input type="hidden" name="existe_adjunto" value="0" />';
            }
            ?>
            
            
            
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('estructura_nombre'); ?>                    
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["documento_nombre"]; ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('documento_enviar'); ?>                    
                </td>

                <td style="width: 70%;">                

                        <?php            
                        if($tipo_accion == 1)
                        {
                        ?>

                            <input id="usuario_activo1" name="documento_enviar" type="radio" class="" <?php if($arrRespuesta[0]["documento_enviar_codigo"]==0) echo "checked='checked'"; ?> value="0" />
                            <label for="usuario_activo1" class=""><span></span><?php echo $this->mfunciones_generales->GetValorCatalogo(0, 'se_envia'); ?></label>

                            &nbsp;&nbsp;

                            <input id="usuario_activo2" name="documento_enviar" type="radio" class="" <?php if($arrRespuesta[0]["documento_enviar_codigo"]==1) echo "checked='checked'"; ?> value="1" />
                            <label for="usuario_activo2" class=""><span></span><?php echo $this->mfunciones_generales->GetValorCatalogo(1, 'se_envia'); ?></label>

                        <?php            
                        }
                        else
                        {
                        ?>

                            <input id="usuario_activo1" name="documento_enviar" type="radio" class="" checked='checked' value="0" />
                            <label for="usuario_activo1" class=""><span></span><?php echo $this->mfunciones_generales->GetValorCatalogo(0, 'se_envia'); ?></label>

                            &nbsp;&nbsp;

                            <input id="usuario_activo2" name="documento_enviar" type="radio" class="" value="1" />
                            <label for="usuario_activo2" class=""><span></span><?php echo $this->mfunciones_generales->GetValorCatalogo(1, 'se_envia'); ?></label>

                        <?php            
                        }
                        ?>

                </td>

            </tr>
            
			<?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('documento_pdf'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon='Sólo si se envía el documento al cliente, adjunte un Documento en formato PDF' data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">
				
                    <label for="file-upload" class="custom-file-upload">
                        <?php echo $this->lang->line('TablaOpciones_SubirDocumento'); ?>
                    </label>
                    <input id="file-upload" type="file" name="documento_pdf" accept=".pdf" />
                    
                    <span id="MensajeArchivo" class="ocultar_elemento">
                        <?php echo $this->lang->line('acta_excepcion_pdf_ok'); ?>
                    </span>
                    
                    <?php
                    
                        if(isset($arrRespuesta[0]["documento_enviar_codigo"]) && $arrRespuesta[0]["documento_enviar_codigo"] == 1 && $arrRespuesta[0]["documento_enlace"] != false)
                        {
                            echo $this->lang->line('documento_tiene_adjunto');
                        }
                    
                    ?>
					
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('documento_codigo'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon='Sólo aplica a Onboarding' data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["documento_codigo"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('documento_mandatorio'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon='Sólo aplica a Onboarding' data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["documento_mandatorio"]; ?>
                </td>

            </tr>
			
        </table>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Documento/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>
        
    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"><?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "><?php echo $this->lang->line('documento_Pregunta'); ?></div>
        
            <div style="clear: both"></div>
        
            <br />

        <div class="PreguntaConfirmar">
            <?php echo $this->lang->line('PreguntaContinuar'); ?>
        </div>

        <div class="Botones2Opciones">
            <a onclick="OcultarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a id="btnGuardarDatosLista" name="btnGuardarDatosLista" onclick="document.getElementById('frm1').submit(); return false;" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
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
    
    </form>
</div>