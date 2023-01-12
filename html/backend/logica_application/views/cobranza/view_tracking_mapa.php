<?php 
if(isset($valoresFiltro))
{
    echo "<div class='mensaje_resultado'> " . $valoresFiltro . " </div> <br />";
} 
?>
        
    <iframe frameborder="0" scrolling="no" class="mapa_iframe" src="<?php echo (site_url('Tracking/Norm/Mapa'));?>">> </iframe>
    
<div style="text-align: right;">
    <span style="font-style: italic; font-weight: bold;"> <?php echo $this->lang->line('norm_marcador_mapa'); ?> </span> <img src="html_public/imagenes/marcador_azul.png" style="vertical-align: middle; width: 25px;" />
</div>