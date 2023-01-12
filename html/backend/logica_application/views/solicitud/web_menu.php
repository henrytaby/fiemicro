
<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
        
            <div class="FormularioSubtitulo"> <i class="fa fa-handshake-o" aria-hidden="true"></i> <?php echo $this->lang->line('RegistroWebTitulo'); ?> </div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('RegistroWebSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <br /><br /><br /><br />
        
        <div align="left" class="Botones2Opciones">
            <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Solicitud/Interno/Mantenimiento');">
                <?php echo $this->lang->line('RegistroWeb_mantenimiento'); ?>
            </span>
        </div>
        
        <div style="clear: both"></div>
		
		<div id="divErrorBusqueda" class="mensajeBD">

        </div>

    </div>
</div>