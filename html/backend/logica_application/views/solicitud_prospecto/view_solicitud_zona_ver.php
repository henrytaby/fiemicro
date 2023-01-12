
<div style="overflow-y: auto; height: 450px;">

    <div class="FormularioSubtitulo"> <?php echo $this->lang->line('SolicitudTitulo_zona'); ?></div>
    <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('SolicitudSubitulo_zona'); ?></div>

    <div style="clear: both"></div>

    <?php // Lógica para actualizar el mapa y ver la ubicación actual ?>        
    <script>            
        function refresh_iframe(id) {
            document.getElementById(id).src = document.getElementById(id).src;
        }            
    </script>

    <div align="center">
        <span class="BotonSimpleGrande" style="width: 50% !important;" onclick="document.getElementById('mapa_visor').contentWindow.actual()">
            <?php echo $this->lang->line('ejecutivo_ubicacion_actual'); ?>
        </span>
    </div>
    
    <iframe id="mapa_visor" frameborder="0" scrolling="no" class="mapa_iframe" style="height: 475px !important;" src="<?php echo (site_url('Solicitud/Zona/Mapa') . '?estructura_id=' . $estructura_id);?>">>    </iframe>

    <div id="divErrorBusqueda" class="mensajeBD"> </div>

</div>