
<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <span class="PreguntaConfirmar">
            <?php echo $texto; ?>
            <br /><br />
        </span>
        
        <div class="">
            <a onclick="Ajax_CargarOpcionMenu('Menu/Principal');" class="BotonMinimalista"> <?php echo 'Volver al ' . $this->lang->line('MenuPrincipal'); ?> </a>
        </div>

        <div style="clear: both"></div>
        
        <div id="divErrorBusqueda" class="mensajeBD"> </div>

    </div>
</div>