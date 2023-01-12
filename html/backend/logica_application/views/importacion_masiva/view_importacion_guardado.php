<script type="text/javascript">

    function Ajax_CargarAccion_EnviarDocumentosEA(codigo_prospecto) {
        var strParametros = "&codigo_prospecto=" + codigo_prospecto;
        Ajax_CargadoGeneralPagina('Solicitud/Enviar/Documentos', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
        
</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <span class="PreguntaConfirmar">
            <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> <?php echo $texto; ?>
            <br /><br />
        </span>
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Importacion/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('ImportacionFormTitulo'); ?> </a>
        </div>

        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Consultas/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('ConsultaTitulo'); ?> </a>
        </div>
        
        <div style="clear: both"></div>
        
        <div id="divErrorBusqueda" class="mensajeBD"> </div>

    </div>
</div>