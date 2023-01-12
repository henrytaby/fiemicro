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
    
    $('input[type=file]').change(function () {

        var id_element = $(this).attr('id');

        document.getElementById("texto_contrato").innerHTML = "";
        $('#botonGuardado').hide();

        var val = $(this).val().toLowerCase();
        var regex = new RegExp("(.*?)\.(pdf)$");
        if(!(regex.test(val))) 
        {
          $(this).val('');
          alert('Archivo no válido, por favor seleccione un documento PDF.');
        }
        else if((this.files[0].size/1024/1024) > 15){ // Size in MB
          alert("El archivo no puede superar los 15MB");
          this.value = "";
        }
        else
        {
            document.getElementById("nombre_contrato").value  = $('#file1').val().split('\\').pop();
            document.getElementById("texto_contrato").innerHTML = "<i class='fa fa-thumbs-o-up' aria-hidden='true'></i> Preparado para subir ->" + $('#file1').val().split('\\').pop();
            $('#botonGuardado').fadeIn();
        }

    });

</script>

<div id="divVistaMenuPantalla" align="center">

    <form action="Afiliador/Completar/AuxGuardar" method="post" accept-charset="utf-8" enctype="multipart/form-data" name="frm1" id="frm1">
    
    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('CompletarTitulo') . ' Onboarding' . $nombre_region; ?></div>
            <div class="FormularioSubtituloComentarioNormal ">Con este paso completará el proceso del registro. Así mismo, si lo requiere, establezca si se notificará al solicitante con la plantilla de correo electrónico establecida, y registre un texto personalizado (opcional).<br /><br />Utilizar función para <span onclick="Ajax_CargarAccion_CompletarSolicitud(<?php if(isset($arrRespuesta[0]["terceros_id"])){ echo $arrRespuesta[0]["terceros_id"]; } ?>, 2)" style="cursor: pointer; text-decoration: underline;"> cargar automáticamente </span> el PDF del contrato.<br /><br /><div class="mensaje_resultado" style="width: 100%; text-align: center;"> <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Importante: En este apartado podrá cargar (upload) un documento PDF del contrato de forma manual. Se recomienda que esta acción sea realizada con mayor preferencia desde la funcionalidad automática.</div></div>
        
        <div style="clear: both"></div>
        
        <br />

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
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td valign="middle" style="width: 30%; font-weight: bold;">
                    PDF del Contrato (Mandatorio)
                </td>

                <td valign="middle" style="width: 70%;">

                    <label for="file1" class="custom-file-upload"> Seleccionar PDF </label>

                    <input type="file" name="documento_pdf" id="file1" accept="application/pdf">
                    
                    <input type="hidden" id="nombre_contrato" name="nombre_contrato" value="" />

                    <span id="texto_contrato"> </span>

                </td>

            </tr>
            
            <?php $strClase = "FilaGris"; ?>
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    ¿Notificar al solicitante por Correo Electrónico?
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Puede editar la plantilla de Correo 'Onboarding - Notificar Completado'." data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["rechazado_envia"]; ?>
                </td>
                
            </tr>
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <i class='fa fa-angle-double-right' aria-hidden='true'></i> Texto Personalizado
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="El texto es opcional. El texto personalizado se enviará donde se coloque la variable {onboarding_completar_texto}" data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["rechazado_texto"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <i class='fa fa-angle-double-right' aria-hidden='true'></i> (Opcional) Seleccione los PDF a adjuntar
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon="Es opcional puede proceder sin adjuntar PDFs. Los documentos listados corresponden a los configurados en el apartado de Tipos de Registro y fueron marcados como 'Se Envía'" data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">
                        
                    <?php

                        if(isset($arrDocs[0]))
                        {
                            $i = 0;
                            $checked = '';

                            foreach ($arrDocs as $key => $value) 
                            {
                                $checked = 'checked="checked"';
                                //$checked = '';
                                
                                echo '<input id="documento' . $i , '" type="checkbox" name="documento_list[]" '. $checked .' value="' . $value["documento_id"] . '">';
                                echo '<label for="documento' . $i , '"><span></span>' . $value["documento_nombre"] . '</label>';
                                echo '<br />';

                                $i++;
                            }
                        }
                        else
                        {
                            echo $this->lang->line('TablaNoRegistrosMinimo');
                        }

                    ?>
                </td>

            </tr>
            
        </table>

        <br /><br /><br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Afiliador/Supervisor/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
            
        <div class="Botones2Opciones">
            <a style="display: none;" id="botonGuardado" onclick="MostrarConfirmación();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
        <div style="clear: both"></div>

    </div>
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class="PreguntaTitulo"> <?php echo $this->lang->line('PreguntaTitulo'); ?></div>
            <div class="PreguntaTexto ">Completar el registro con los datos indicados y documento PDF seleccionado. <br /><br /> Si seleccionó la notificación al solicitante, se enviará el Correo Electróncio respectivo incluido el texto personalizado registrado.</div>
        
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