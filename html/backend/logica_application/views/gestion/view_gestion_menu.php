
<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
        
            <div class="FormularioSubtitulo"> Gestión de Afiliación e Información de Empresas </div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('conf_credmenu_Subtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <br />
        
        <div align="left" class="Botones2Opciones">
            
           <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Registro/Prospecto/Ver');">
               <i class="fa fa-inbox" aria-hidden="true"></i> Gestión Documental de Afiliación <br /> y Consolidación
            </span>
        </div>
        
        <div align="left" class="Botones2Opciones">
            <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Registro/Empresa/Ver');">
                <i class="fa fa-address-book-o" aria-hidden="true"></i> Actualización de Información de <br /> Empresas Registradas
            </span>
        </div>
        
        <div style="clear: both"></div>
		
		<div id="divErrorBusqueda" class="mensajeBD">

        </div>

    </div>
</div>