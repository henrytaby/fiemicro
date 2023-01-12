<?php 
if(isset($valoresFiltro))
{
    echo "<div class='mensaje_resultado'> " . $valoresFiltro . " </div> <br />";
} 
?>
        
    <iframe frameborder="0" scrolling="no" class="mapa_iframe" src="<?php echo (site_url('Seguimiento/Resultado/Mapa'));?>">> </iframe>
    
<div style="text-align: right;">    
    <span style="font-style: italic; font-weight: bold;"> <?php echo $this->lang->line('ejecutivo_seguimiento_prospecto'); ?> </span> <img src="html_public/imagenes/marcador_naranja.png" style="vertical-align: middle; width: 25px;" />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <span style="font-style: italic; font-weight: bold;"> <?php echo $this->lang->line('ejecutivo_seguimiento_mantenimiento'); ?> </span> <img src="html_public/imagenes/marcador_azul.png" style="vertical-align: middle; width: 25px;" />
</div>