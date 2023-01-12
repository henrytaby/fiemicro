
<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('EjecutivoTitulo_Mapa') . $this->mfunciones_generales->GetValorCatalogo($_SESSION["identificador_tipo_perfil_app"], 'tipo_perfil_app'); ?></div>
        
        <div style="clear: both"></div>
        
        <br />
        
        <iframe frameborder="0" scrolling="no" class="mapa_iframe" src="<?php echo (site_url('Ejecutivo/Mapa/Mapa'));?>">>    </iframe>
        
        <div id="divErrorBusqueda" class="mensajeBD"> </div>
                
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Ejecutivo/Ver/<?php echo $_SESSION["identificador_tipo_perfil_app"]; ?>');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>

    </div>
</div>