
<div style="overflow-y: auto; height: 400px;">

    <div class="FormularioSubtitulo"> <?php echo $this->lang->line('SolicitudTitulo_mapa'); ?></div>

    <div style="clear: both"></div>

    <iframe frameborder="0" scrolling="no" class="mapa_iframe" style="height: 475px !important;" src="<?php echo (site_url('Solicitud/Mapa/Mapa') . '?estructura_id=' . $estructura_id);?>">>    </iframe>

    <div id="divErrorBusqueda" class="mensajeBD"> </div>

</div>