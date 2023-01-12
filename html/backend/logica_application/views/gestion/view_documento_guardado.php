<script type="text/javascript">

    function Ajax_CargarAccion_Prospecto(codigo) {
        var strParametros = "&codigo=" + codigo + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Ejecutivo/Prospecto/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <span class="PreguntaConfirmar">
            <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> <?php echo $texto; ?>
            <br /><br />
        </span>
        
        <div class="Botones2Opciones" style="float: none;">
            
            <?php
            
                $direccion_bandeja = 'Menu/Principal';
            
                if(isset($_SESSION['direccion_bandeja_actual']))
                {
                    $direccion_bandeja = $_SESSION['direccion_bandeja_actual'];
                }
            
            ?>
            
            <a onclick="<?php echo $direccion_bandeja; ?>" class="BotonMinimalista"> <?php echo $this->lang->line('prospecto_obs_doc_volver'); ?> </a>
        </div>

        <div style="clear: both"></div>
        
        <div id="divErrorBusqueda" class="mensajeBD"> </div>

    </div>
</div>