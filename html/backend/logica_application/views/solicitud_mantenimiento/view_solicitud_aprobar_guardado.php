<script type="text/javascript">
        
</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <span class="PreguntaConfirmar">
            <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> <?php echo $texto; ?>
            <br /><br />
        </span>
        
        <div class="Botones2Opciones" style="float: none;">
            <a onclick="Ajax_CargarOpcionMenu('Solicitud/Mantenimiento/Ver');" class="BotonMinimalista"> <i class="fa fa-list-ol" aria-hidden="true"></i> <?php echo 'Ver ' . $this->lang->line('solicitud_estado_pendiente'); ?> </a>
        </div>

        <div style="clear: both"></div>
        
        <div id="divErrorBusqueda" class="mensajeBD"> </div>

    </div>
</div>