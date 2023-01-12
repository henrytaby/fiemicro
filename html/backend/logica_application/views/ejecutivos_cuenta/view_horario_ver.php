

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
        
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('HorarioTitulo') . $this->mfunciones_generales->GetValorCatalogo($_SESSION["identificador_tipo_perfil_app"], 'tipo_perfil_app'); ?> </div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('HorarioSubtitulo'); ?></div>
        
        <div style="clear: both"></div>

        <div id="divErrorListaResultado" class="mensajeBD"> </div>
        
        <div style="overflow: hidden; width: 100%;">            
            <iframe frameborder="0" scrolling="no" class="calendario_iframe" src="<?php echo (site_url('Ejecutivo/Horario/Ver?estructura_id=' . $estructura_id));?>">>    </iframe>
        </div>
        
        <br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Ejecutivo/Ver/<?php echo $_SESSION["identificador_tipo_perfil_app"]; ?>');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
        
    </div>    
    
</div>