<script type="text/javascript">

    function RefreshTrackingFlujoCOBIS(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina("Afiliador/Tracking/FlujoCOBIS", "divTrackingFlow", "divTrackingFlow", "SIN_FOCUS", strParametros);
    }
    
    RefreshTrackingFlujoCOBIS(<?php echo $terceros_id; ?>);
    
</script>

<div id="divVistaMenuPantalla" align="center">
    
    <div id="confirmacion" class="PreguntaConfirmacion TamanoContenidoGeneral">

        <div class="FormularioSubtituloImagenPregunta"> </div>
        
            <div class='PreguntaTitulo' style='padding-top: 5px;'> ACCIÓN REALIZADA </div>
            <div class='PreguntaTexto ' style='padding-top: 5px; font-size: 13px;'>
                <?php echo sprintf($this->lang->line('f_cobis_tracking_pnl_resultado'), $terceros_id); ?>
            </div>
        
            <div style="clear: both"></div>
            <br />
            
            <div id="divTrackingFlow"></div>
            
            <div style="clear: both"></div>
            <br />

        <div class="Centrado" style="width: 70%; text-align: center; padding-top: 10px;">
            <a class="BotonMinimalista" onclick="Ajax_CargarAccion_BandejaSolicitud('<?php echo $estado; ?>');">
                <span><?php echo $this->lang->line('prospecto_obs_doc_volver'); ?></span>
            </a>
        </div>
        
        <div style="clear: both"></div>

        <?php if (isset($respuesta)) { ?>
            <div class="mensajeBD"> 
                <div style="padding: 15px;">
                    <?php echo $respuesta ?>
                </div>
            </div>
        <?php } ?>

        <div id="divErrorListaResultado" class="mensajeBD"> </div>
		
    </div>
    
</div>