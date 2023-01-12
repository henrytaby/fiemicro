<script type="text/javascript"
        src="html_public/js/lib/ckeditor/ckeditor.js"></script>

<script type="text/javascript">
<?php echo $strValidacionJqValidate; ?>
    
    function Ajax_CargarAccion_GuardarPlantilla() {
        
        var codigo = $('#conf_correo_id').val();
        var nombre = $('#conf_plantilla_nombre').val();
        var titulo = $('#conf_plantilla_titulo_correo').val();
        var contenido = CKEDITOR.instances.conf_plantilla_contenido.getData();
        
        contenido = contenido.replace(/&aacute;/g, "á");
        contenido = contenido.replace(/&eacute;/g, "é");
        contenido = contenido.replace(/&iacute;/g, "í");
        contenido = contenido.replace(/&oacute;/g, "ó");
        contenido = contenido.replace(/&uacute;/g, "ú");
        
        contenido = contenido.replace(/&Aacute;/g, "Á");
        contenido = contenido.replace(/&Eacute;/g, "É");
        contenido = contenido.replace(/&Iacute;/g, "Í");
        contenido = contenido.replace(/&Oacute;/g, "Ó");
        contenido = contenido.replace(/&Uacute;/g, "Ú");
        
        contenido = contenido.replace(/&gt;/g, ">");
        contenido = contenido.replace(/&lt;/g, "<");
        
        contenido = contenido.replace(/&quot;/g, "\"");
        contenido = contenido.replace(/&#039;/g, "\'");        
		
        contenido = contenido.replace(/&nbsp;/g, " ");
        contenido = contenido.replace(/nbsp;/g, " ");
		
        contenido = contenido.replace(/&amp;/g, " ");
        		
        contenido = contenido.replace(/&nbsp;/gi,' ');
        
        contenido = contenido.replace(/&iexcl;/gi,'¡');
        
        contenido = contenido.replace(/style/gi,'st|le');
        
        var strParametros = "&conf_correo_id=" + codigo + "&conf_plantilla_nombre=" + nombre + "&conf_plantilla_titulo_correo=" + titulo + "&conf_plantilla_contenido=" + contenido;
        Ajax_CargadoGeneralPagina('Conf/Correo/Plantilla/Guardar', 'divVistaMenuPantalla', "divErrorListaResultado", '', strParametros);
    }
    
    $('#conf_plantilla_nombre').attr('disabled','disabled');
            
</script>

<br /><br /><br /><br />

    <form id="FormularioRegistroLista" method="post" enctype="multipart/form-data">

                            <?php // COLOCAR AQUI LA RUTA PARA REDIRECCIONAR ?>

        <input type="hidden" name="redireccionar" value="Campana/Ver" />

        <input type="hidden" name="conf_correo_id" id="conf_correo_id" value="<?php if(isset($arrRespuesta[0]["conf_plantilla_id"])){ echo $arrRespuesta[0]["conf_plantilla_id"]; } ?>" />

        <table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

            <?php $strClase = "FilaBlanca"; ?>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_plantilla_nombre'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["conf_plantilla_nombre"]; ?>
                </td>

            </tr>

            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_plantilla_titulo_correo'); ?>
                </td>

                <td style="width: 70%;">                
                    <?php echo $arrCajasHTML["conf_plantilla_titulo_correo"]; ?>
                </td>

            </tr>
            
            <?php //$strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca"; ?> 
            <tr class="<?php echo $strClase; ?>">

                <td style="width: 30%; font-weight: bold;">
                    <?php echo $this->lang->line('conf_plantilla_variables_correo'); ?>
                    <span class="AyudaTooltip" data-balloon-length="medium" data-balloon='<?php echo $this->lang->line('Ayuda_variables_correo'); ?>' data-balloon-pos="right"> </span>
                </td>

                <td style="width: 70%;">                
                    <?php echo $this->lang->line('conf_plantilla_variables_correo_def'); ?>
                </td>

            </tr>

        </table>
        
        <textarea name="conf_plantilla_contenido" id="conf_plantilla_contenido"><?php echo $arrRespuesta[0]["conf_plantilla_contenido"]; ?></textarea>
        
        <script>
            CKEDITOR.replace( 'conf_plantilla_contenido', {
                customConfig: 'config.js'
            });
        </script>
        
       <br />
        
        <div class="Botones2Opciones" style="float: right;">
            <a onclick="Ajax_CargarAccion_GuardarPlantilla();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
        </div>
        
    </form>