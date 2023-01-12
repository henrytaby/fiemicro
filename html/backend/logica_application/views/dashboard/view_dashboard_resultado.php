
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
        
        var strParametros = "&sel_departamento=" + $("#sel_departamento_tabla").val()
            + "&sel_agencia=" + $("#sel_agencia_tabla").val()
            + "&sel_oficial=" + $("#sel_oficial_tabla").val()
            + "&campoFiltroFechaDesde=" + $("#campoFiltroFechaDesde_tabla").val()
            + "&campoFiltroFechaHasta=" + $("#campoFiltroFechaHasta_tabla").val()
            + "&campoFiltroFechaDCDesde=" + $("#campoFiltroFechaDCDesde_tabla").val()
            + "&campoFiltroFechaDCHasta=" + $("#campoFiltroFechaDCHasta_tabla").val()
    
            + "&etapa=" + etapa + "&tipo=" + tipo;
        Ajax_CargadoGeneralPagina('Dashboard/Reporte/Tabla', 'divResultadoFunnelTabla', "divErrorBusqueda", 'SLASH', strParametros);
    }

</script>
    
<style> <?php echo $this->mfunciones_dashboard->ObtenerEstiloFunnel(); ?> </style>

<div style="width: 100%; text-align: center;">

    <div class="inv-piramide-titulo">ESTADO DE EVOLUCIÓN - CONSOLIDADO</div>
    
    <br />
    
    <div class="col-sm-6" style="max-width: 570px;">

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
            <strong> <i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo $this->lang->line('infoDashboard1'); ?> </strong>
            
            <br />
            
<?php

$ayda_etapas = 'data-balloon-length="large" data-balloon="
Las etapas corresponden a las establecidas para 
el flujo de Estudio de Crédito, mostrando la 
cantidad y monto agrupado en cada etapa.

Se consideran los Estudios y Solicitudes 
de Crédito desde la fecha ' . $this->mfunciones_generales->getFormatoFechaD_M_Y($this->lang->line('dashboard_fecha_corte')) . '.

Para las Solicitudes de Crédito:
- La primera etapa del embudo considera también 
las Solicitudes de Crédito creadas y asignadas 
antes de ser consolidadas o convertidas a 
Estudio de Crédito.
- La cuarta y quinta etapa del embudo considera 
también las Solicitudes de Crédito con sus rubros
operativos (\'Ingreso Fijo\', \'Agropecuario\', 
\'Bajo Línea\', \'Micro B\', \'PYME\' o 
\'Corporativo\') que hayan sido consolidadas.
- Los registros desembolsados consideran también 
las Solicitudes de Crédito con sus rubros operativos
marcadas como Desembolsado en COBIS.
- Los registros rechazados consideran también las 
Solicitudes de Crédito con sus rubros operativos 
marcadas como Evaluación JDA Rechazado o las 
rechazadas por bandeja.

"

data-balloon-pos="up" data-balloon-break=""';

?>
            
            <i class="fa fa-info-circle" aria-hidden="true"></i> <strong> <i> Info del reporte,</i> </strong> <span <?php echo $ayda_etapas; ?> style="text-decoration: underline; cursor: pointer;"><strong>ver ayuda</strong></span>.
            
        </div>
        
    </div>

    <div class="col-sm-6">

        <br /><br /> 
        
        <table class="tblListas Centrado" style="width: 100%;" border="0">
            
            <tr class="FilaCabecera">
                <th style="width:40%;">
                    
                </th>

                <th style="width:29%;">
                    <strong>N° Registros</strong>
                </th>

                <th style="width:29%;">
                    <strong>Monto Bs.</strong>
                </th>
                
                <th rowspan="2" style="width:2%;">
                    <strong><i class="fa fa-cogs" aria-hidden="true" title="Opciones"></i></strong>
                </th>

            </tr>
            
            <tr class="FilaCabecera">
                <th style="width:40%; text-align: left;">
                    <strong>SOLICITUDES INGRESADAS</strong>
                </th>

                <th style="width:30%; text-align: right !important;">
                    <?php echo number_format($funnel->total_tabla_contador, 0, '.', ','); ?>
                </th>

                <th style="width:30%; text-align: right !important;">
                    <?php echo number_format($funnel->total_tabla_monto, 2, '.', ','); ?>
                </th>

            </tr>
            
            <tr class="FilaBlanca">
                <td style="text-align: justify;">
                    EN PROCESO
                </td>

                <td style="text-align: right;">
                    <?php echo number_format($funnel->proceso_contador, 0, '.', ','); ?>
                </td>

                <td style="text-align: right;">
                    <?php echo number_format($funnel->proceso_monto, 2, '.', ','); ?>
                </td>
                
                <td style="text-align: center;">
                    
                    <?php echo $this->mfunciones_dashboard->ObtenerAyudaEtapa(-1, $funnel->proceso_contador, $funnel->proceso_monto, $funnel->total_tabla_contador, $funnel->total_tabla_monto) ?>
                    
                </td>
            </tr>
            
            <tr class="FilaBlanca">
                <td style="text-align: justify;">
                    APROBADAS
                </td>

                <td style="text-align: right;">
                    <?php echo number_format($funnel->aprobado_contador, 0, '.', ','); ?>
                </td>

                <td style="text-align: right;">
                    <?php echo number_format($funnel->aprobado_monto, 2, '.', ','); ?>
                </td>
                
                <td style="text-align: center;">
                    
                    <?php echo $this->mfunciones_dashboard->ObtenerAyudaEtapa(22, $funnel->aprobado_contador, $funnel->aprobado_monto, $funnel->total_tabla_contador, $funnel->total_tabla_monto) ?>
                    
                </td>
            </tr>
            
            <tr class="FilaBlanca">
                <td style="text-align: justify;">
                    DESEMBOLSADAS
                </td>

                <td style="text-align: right;">
                    <?php echo number_format($funnel->desembolso_contador, 0, '.', ','); ?>
                </td>

                <td style="text-align: right;">
                    <?php echo number_format($funnel->desembolso_monto, 2, '.', ','); ?>
                </td>
                
                <td style="text-align: center;">
                    
                    <?php echo $this->mfunciones_dashboard->ObtenerAyudaEtapa(24, $funnel->desembolso_contador, $funnel->desembolso_monto, $funnel->total_tabla_contador, $funnel->total_tabla_monto) ?>
                    
                </td>
            </tr>
            
            <tr class="FilaBlanca">
                <td style="text-align: justify;">
                    RECHAZADAS
                </td>

                <td style="text-align: right;">
                    <?php echo number_format($funnel->rechazado_contador, 0, '.', ','); ?>
                </td>

                <td style="text-align: right;">
                    <?php echo number_format($funnel->rechazado_monto, 2, '.', ','); ?>
                </td>
                
                <td style="text-align: center;">
                    
                    <?php echo $this->mfunciones_dashboard->ObtenerAyudaEtapa(23, $funnel->rechazado_contador, $funnel->rechazado_monto, $funnel->total_tabla_contador, $funnel->total_tabla_monto) ?>
                    
                </td>
            </tr>
            
            <?php
            if((int)$funnel->desembolso_fecha_switch == 1)
            {
            ?>
                <tr class="FilaBlanca" style="background-color: #e2f5ff !important; font-weight: bold;">
                    <td style="text-align: justify;">
                        DESEMBOLSADAS EN EL RANGO SELECCIONADO
                    </td>

                    <td style="text-align: right;">
                        <?php echo number_format($funnel->desembolso_fecha_contador, 0, '.', ','); ?>
                    </td>

                    <td style="text-align: right;">
                        <?php echo number_format($funnel->desembolso_fecha_monto, 2, '.', ','); ?>
                    </td>

                    <td style="text-align: center;">

                        <?php echo $this->mfunciones_dashboard->ObtenerAyudaEtapa(9024, $funnel->desembolso_fecha_contador, $funnel->desembolso_fecha_monto, $funnel->total_tabla_contador, $funnel->total_tabla_monto) ?>

                    </td>
                </tr>
            <?php
            }
            ?>

        </table>
        
        <div style="text-align: right;">
            <br />
            <strong> <i class="fa fa-info-circle" aria-hidden="true"></i> <i> Se consideran todas las solicitudes ingresadas en el periodo.</i></strong>
        </div>
        
    </div>

</div>

<div style="clear: both"></div>
<br /><br />
<div id="divResultadoFunnelTabla"></div>

