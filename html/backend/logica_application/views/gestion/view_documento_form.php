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
    
    function Ajax_CargarAccion_Prospecto(codigo) {
        var strParametros = "&codigo=" + codigo + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Ejecutivo/Prospecto/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> Se procederá a Subir un Nuevo Documento al Registro </div>
            <div class="FormularioSubtituloComentarioNormal "> Para subir un nuevo archivo PDF y asociarlo al documento seleccionado del Registro, por favor proceda a seleccionarlo. Verifique que el Documento sea correcto.  </div>
        
        <div style="clear: both"></div>
                
        <br />

        <form action="Registro/Documento/Guardar" method="post" accept-charset="utf-8" enctype="multipart/form-data" name="frm1" id="frm1">

            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

            <input type="hidden" name="redireccionar" value="" />

            <input type="hidden" name="documento_codigo" value="<?php echo $documento_codigo; ?>" />
            <input type="hidden" name="prospecto_codigo" value="<?php echo $prospecto_codigo; ?>" />
            <input type="hidden" name="codigo_tipo_persona" value="<?php echo $codigo_tipo_persona; ?>" />
            
        <table class="tablaresultados Mayuscula" style="width: 80% !important;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="FilaGris">

                <td style="width: 30%; font-weight: bold;">
                    <br /><?php echo $this->lang->line('observacion_documento'); ?><br /><br />
                </td>

                <td style="width: 70%; font-weight: bold;">                
                    <?php echo $documento_nombre; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="FilaGris">

                <td style="width: 30%; font-weight: bold;">
                    <br />Solicitante<br /><br />
                </td>

                <td style="width: 70%; font-weight: bold;">                
                    <?php echo $arrProspecto[0]['general_solicitante']; ?>
                </td>

            </tr>
            
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('documento_pdf'); ?>
                </td>

                <td style="width: 70%;">
				
                    <label for="file-upload" class="custom-file-upload">
                        <?php echo $this->lang->line('TablaOpciones_SubirDocumento'); ?>
                    </label>
                    <input id="file-upload" type="file" name="documento_pdf" accept=".pdf" />
                    
                    <span id="MensajeArchivo" class="ocultar_elemento">
                        <?php echo $this->lang->line('acta_excepcion_pdf_ok'); ?>
                    </span>
	
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
            
            <a onclick="<?php echo $direccion_bandeja; ?>" class="BotonMinimalista"> <?php echo $this->lang->line('BotonSalir'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>
        
        

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto "> Se procederá a Subir el archivo seleccionado y será asociado al Documento del Cliente indicado. </div>
        
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
</div>