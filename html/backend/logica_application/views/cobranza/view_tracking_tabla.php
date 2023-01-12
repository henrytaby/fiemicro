<script type="text/javascript">
<?php
// Si no existe informaci?n, no se mostrar? como tabla con columnas con ?rden
if(count($_SESSION['arrResultadoSeguimiento'][0]) > 0 && $formato_reporte == 2)
{
?>  
    $('#divDetalle').dataTable({
        "searching": true,
        "iDisplayLength": <?php echo PAGINADO_TABLA; ?>,
        "bAutoWidth": false,
        "bLengthChange": false,
        "aaSorting": [[6, "asc"]], // Sort by first column descending,
        "oLanguage": idioma_table
    });
<?php
}
?>
    function Ajax_CargarAccion_DetalleNorm(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Norm/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
</script>

<?php $cantidad_columnas = 11;?>

<?php 
if(isset($valoresFiltro))
{
    echo "<div class='mensaje_resultado'> " . $valoresFiltro . " </div> <br />";
} 
?>

<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
    <thead>

        <tr class="FilaCabecera">
		
            <th  style="text-align: left !important;" colspan="<?php echo $cantidad_columnas; ?>">

                <div class="BotonExportar" onclick="window.open('Tracking/Norm/Excel', '_blank');">
                    <strong><span><?php echo $this->lang->line('exportar'); ?></span><img src="html_public/imagenes/descenso_diminuto.png" style="vertical-align: middle;" /></strong>
                </div>
            </th>

    </tr>
        
        <tr class="FilaCabecera">

            <!-- Similar al EXCEL -->

            <th style="width:6%; font-weight: bold;">
               <?php echo $this->lang->line('norm_col_codigo'); ?> </th>
            <th style="width:9%; font-weight: bold;">
               <?php echo $this->lang->line('norm_col_agencia'); ?> </th>
            <th style="width:9%; font-weight: bold;">
               <?php echo $this->lang->line('norm_col_agente'); ?> </th>
            <th style="width:10%; font-weight: bold;">
               <?php echo $this->lang->line('norm_col_cliente'); ?> </th>
            <th style="width:10%; font-weight: bold;">
               <?php echo $this->lang->line('norm_col_num_proceso'); ?> </th>
            <th style="width:9%; font-weight: bold;">
               <?php echo $this->lang->line('norm_col_estado'); ?> </th>
            <th style="width:9%; font-weight: bold;">
               <?php echo $this->lang->line('norm_col_fec_registro'); ?> </th>
            <th style="width:9%; font-weight: bold;">
               <?php echo $this->lang->line('norm_col_fec_visita'); ?> </th>
            <th style="width:10%; font-weight: bold;">
               <?php echo $this->lang->line('cv_resultado'); ?> </th>
            <th style="width:9%; font-weight: bold;">
               <?php echo $this->lang->line('cv_fecha_compromiso'); ?> </th>
            <th style="width:10%; font-weight: bold;">
               <?php echo $this->lang->line('cv_checkin'); ?> </th>

            <!-- Similar al EXCEL -->
            
        </tr>
    </thead>
    <tbody>
            <?php

            $mostrar = true;
            if (count($_SESSION['arrResultadoSeguimiento'][0]) == 0) 
            {
                $mostrar = false;
            }

            if ($mostrar) 
            {
                $strClase = "FilaBlanca";
                foreach ($_SESSION['arrResultadoSeguimiento'] as $key => $value) 
                {
                    ?> 
                    <tr class="<?php echo $strClase; ?>">

                        <!-- Similar al EXCEL -->

                        <td style="text-align: center; background-color: #fcfcfc;">
                            <?php
                            echo '<span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleNorm(\'' . $value["norm_id"] . '\')">';
                            echo $this->lang->line('norm_prefijo') . $value["norm_id"];
                            echo '</span>';
                            ?>
                        </td>
                        
                        <td style="text-align: center; background-color: #fcfcfc;">
                            <?php echo $value["agencia_nombre"]; ?>
                        </td>
                        <td style="text-align: center; background-color: #fcfcfc;">
                            <?php echo $value["ejecutivo_nombre"]; ?>
                        </td>
                        <td style="text-align: center; background-color: #fcfcfc;">
                            <?php echo $value["cliente_nombre"]; ?>
                        </td>
                        <td style="text-align: center; background-color: #fcfcfc;">
                            <?php echo $value["registro_num_proceso"]; ?>
                        </td>
                        <td style="text-align: center; background-color: #fcfcfc;">
                            <?php echo $value["registro_consolidado"] . ($value["norm_ultimo_paso_check"] ? '<br /><i>¡Registro Pendiente!</i>' : ''); ?>
                        </td>
                        <td style="text-align: center; background-color: #fcfcfc;">
                            <?php echo $value["fecha_registro"]; ?>
                        </td>
                        <td style="text-align: center;">
                            <?php echo $value["cv_fecha"]; ?>
                        </td>
                        <td style="text-align: center;">
                            <?php echo $value["cv_resultado"]; ?>
                        </td>
                        <td style="text-align: center;">
                            <?php echo $value["cv_fecha_compromiso"]; ?>
                        </td>
                        
                        <td style="text-align: center;">
                            <?php 
                                echo $value["cv_checkin_fecha"] . '<br><a href="https://maps.google.com/?q=' . $value["cv_checkin_geo"] . '" target="_blank" style="font-size: 0.8em;">GEO: ' . $value["cv_checkin_geo"] . '</a>';
                            ?>
                        </td>

                        <!-- Similar al EXCEL -->

                    </tr>
                <?php
                }
                ?>
    </tbody>
                <?php
                $strClase = $strClase == "FilaBlanca" ? "FilaGris" : "FilaBlanca";
                //endfor;
            }
            else 
            {
            ?>
            <tr>
                <td style="width: 55%; color: #25728c;" colspan="<?php echo $cantidad_columnas; ?>">
                    <br><br>
                    <?php echo $this->lang->line('TablaNoRegistros'); ?>
                    <br><br>
                </td>                               
            </tr>
    <?php } ?>
</table>