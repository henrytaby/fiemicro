<script type="text/javascript">

    function Ajax_CargarAccion_VerPlantilla() {
	var codigo_plantilla = $('select[name=plantilla]').val();
        var strParametros = "&codigo_plantilla=" + codigo_plantilla;
        Ajax_CargadoGeneralPagina('Conf/Correo/Plantilla/Cargar', 'ResultadoPlantilla', "divErrorListaResultado", 'SLASH', strParametros);
    }

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('conf_plantilla_correo_Titulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('conf_plantilla_correo_Subtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <br />
        
		<?php
                
        if (count($arrRespuesta[0]) > 0) 
        {
        ?>
            <table style="width: 100%;" border="0">
                <tr>
                    <td style="width: 50%; font-weight: bold;">
                        <?php echo html_select('plantilla', $arrRespuesta, 'conf_plantilla_id', 'conf_plantilla_nombre', '', ''); ?>
                    </td>

                    <td style="width: 50%;">
                        <br /><br />
                        <div class="Botones2Opciones">
                                <a onclick="Ajax_CargarAccion_VerPlantilla();" class="BotonMinimalista"> <?php echo $this->lang->line('BotonAceptar'); ?> </a>
                        </div>
                    </td>
                </tr>		
            </table>
		
        <?php
        }        
        ?>
        
        <div style="clear: both"></div>
        
		<div id="divErrorListaResultado" class="mensajeBD"> </div>
		
        <div id="ResultadoPlantilla" style="width: 100%;"> </div>
        
        <br /><br /><br /><br /><br /><br />

    </div>
    
</div>