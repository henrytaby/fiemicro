
<div>
    
    <div class="FormularioSubtitulo" style="width: 95% !important;"> <?php echo " <i class='fa fa-calendar-check-o' aria-hidden='true'></i> Horario de " . $usuario_nombre; ?></div>

    <div style="clear: both"></div>

    <div id="divErrorListaResultado" class="mensajeBD"> </div>

    <div style="overflow: hidden; width: 100%;">            
        <iframe frameborder="0" scrolling="no" class="calendario_iframe" style="height: 400px !important;" src="<?php echo (site_url('Ejecutivo/Lectura/Horario/Ver?estructura_id=' . $estructura_id));?>">>    </iframe>
    </div>
    
</div>