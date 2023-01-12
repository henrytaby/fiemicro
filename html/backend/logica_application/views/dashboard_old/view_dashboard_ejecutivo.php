
<script type="text/javascript">

    function inv_piramide_values(val)
    {
        $(".inv-piramide_monto, .inv-piramide_registros").hide();

        if(val == 0)
        {
            $(".inv-piramide_monto").fadeIn();
        }
        else
        {
            $(".inv-piramide_registros").fadeIn();
        }
    }
    
    function ReporteFunnelTabla(etapa, tipo) {
        
        $("#divResultadoFunnelTabla").html("");
        
        var strParametros = "&estructura_id=" + <?php echo $sel_oficial; ?>
            + "&sel_departamento=" + $("#sel_departamento_tabla").val()
            + "&sel_agencia=" + $("#sel_agencia_tabla").val()
            + "&sel_oficial=" + $("#sel_oficial_tabla").val()
            + "&campoFiltroFechaDesde=" + $("#campoFiltroFechaDesde_tabla").val()
            + "&campoFiltroFechaHasta=" + $("#campoFiltroFechaHasta_tabla").val()
            + "&etapa=" + etapa + "&tipo=" + tipo
            + "&funnel_tabla_app=" + 1;
        Ajax_CargadoGeneralPagina('Ejecutivo/ReporteFunnel', 'divResultadoFunnelTabla', "divErrorBusqueda", '', strParametros);
        MostrarTabla();
    }
    
    function MostrarFunnel()
    {
        $("#divResultadoFunnelTabla").hide();
        $("#boton_mostrar_funnel").hide();
        $("#divResultadoFunnel").fadeIn();
        $('#titulo_funnel').css('padding','0px');
    }
    
    function MostrarTabla()
    {
        $("#divResultadoFunnel").hide();
        $("#divResultadoFunnelTabla").fadeIn();
        $('#titulo_funnel').css('padding','1px 0px');
    }

    $('#divContenidoGeneral').css('padding-bottom','0px');

</script>
    
<style> <?php echo $this->mfunciones_dashboard->ObtenerEstiloFunnel(); ?> </style>

<div style="overflow-y: auto; height: 400px;">

    <div class="FormularioSubtitulo" style="width: 100%;"> <i class="fa fa-inbox" aria-hidden="true"></i> <?php echo $NombreEjecutivo; ?></div>
    
    <div style="clear: both"></div>
    
    <div class="inv-piramide-titulo" id="titulo_funnel" style="width: 100%; text-align: center; border-radius: 10px; margin-top: 5px; z-index: 1;">

        <table border="0" style="width: 100%">
            <tr>

                <td class="inv-piramide-titulo" style="width: 5%; text-align: right;">
                    <span style="display: none; font-size: 1.2em" id="boton_mostrar_funnel" title="Volver al reporte" class="EnlaceSimple" onclick='MostrarFunnel();'>
                        <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
                    </span>
                </td>

                <td class="inv-piramide-titulo" style="width: 95%; text-align: center;">
                    ESTADO DE EVOLUCIÓN
                </td>
            </tr>
        </table>

    </div>

    <br />

    <div id="divResultadoFunnel" style="width: 100%; text-align: center; margin-top: 15px;">

        <div style="text-align: center; display: inline-flex;">

            <div class="col-sm-12" style="max-width: 570px;">

                <div>

                    <input id="sel_montos" name="inv-piramide_selector" type="radio" class="" onclick="inv_piramide_values(0);" checked="checked">
                    <label for="sel_montos" class="inv-piramide-radio"><span></span>Monto Bs.</label>

                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                    <input id="sel_registros" name="inv-piramide_selector" type="radio" class="" onclick="inv_piramide_values(1);">
                    <label for="sel_registros" class="inv-piramide-radio"><span></span>Registros</label>

                </div>

                <?php echo $chartFunnel; ?>

                <br />
                <div style="text-align: left; padding-left: 20px;">
                    <strong>
                        <i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo $this->lang->line('infoDashboard1'); ?>
                        <br />
                        <i class="fa fa-info-circle" aria-hidden="true"></i> <i>Se consideran los Estudios y Solicitudes de Crédito desde la fecha <?php echo $this->mfunciones_generales->getFormatoFechaD_M_Y($this->lang->line('dashboard_fecha_corte')); ?>.</i>
                    </strong>
                </div>

            </div>

        </div>

    </div>

    <div id="divResultadoFunnelTabla" style="margin-top: -10px;"></div>

</div>