
<script>
    document.getElementById("divContenidoElementoFlotante").style.top = "50px";
</script>

<div style="overflow-y: auto; height: 550px;">
    
    <iframe id="mapa_visor" frameborder="0" scrolling="no" class="mapa_iframe" style="height: 550px !important;" src="<?php echo site_url('Empresa/Geo/Mapa');?>">>    </iframe>

    <div id="divErrorBusqueda" class="mensajeBD"> </div>

</div>