<script type="text/javascript">

    function Ajax_CargarAccion_Prospecto(codigo) {
        var strParametros = "&codigo=" + codigo + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Ejecutivo/Prospecto/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    <?php
    if((int)$codigo_onboarding > 0)
    {
    ?>
        function RefreshTrackingFlujoCOBIS(codigo) {
            var strParametros = "&codigo=" + codigo;
            Ajax_CargadoGeneralPagina("Afiliador/Tracking/FlujoCOBIS", "divTrackingFlow", "divTrackingFlow", "SIN_FOCUS", strParametros);
        }

        RefreshTrackingFlujoCOBIS(<?php echo $codigo_onboarding; ?>);
    <?php
    }
    ?>

</script>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <br /><br />

        <span class="PreguntaConfirmar">
            <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> El Cliente se Consolidó Correctamente y/o <br/> las Observaciones se marcaron como Subsanadas.
            <br /><br />
        </span>
        
        <?php
        if((int)$codigo_onboarding > 0)
        {
        ?>
            <div class='PreguntaTexto' style="float: none;">
                <?php echo sprintf($this->lang->line('f_cobis_tracking_pnl_resultado'), $codigo_onboarding); ?>
            </div>
        
            <div style="clear: both"></div>
            <br />
            
            <div id="divTrackingFlow"></div>
            
            <div style="clear: both"></div>
            <br /><br />
            
        <?php
        }
        ?>
        
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