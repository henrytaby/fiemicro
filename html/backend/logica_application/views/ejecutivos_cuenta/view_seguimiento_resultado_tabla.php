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
        "aaSorting": [[0, "asc"]], // Sort by first column descending,
        "oLanguage": idioma_table
    });
<?php
}
?>
    function Ajax_CargarAccion_DetalleSolicitudCred(codigo, vista) {
        var strParametros = "&codigo=" + codigo + "&vista=" + vista;
        Ajax_CargadoGeneralPagina('SolWeb/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_DetalleProspecto(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Prospecto/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
</script>

<?php $cantidad_columnas = 8;?>

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

                    <div class="BotonExportar" onclick="window.open('Seguimiento/Resultado/Excel', '_blank');">
                        <strong><span><?php echo $this->lang->line('exportar'); ?></span><img src="html_public/imagenes/descenso_diminuto.png" style="vertical-align: middle;" /></strong>
                    </div>
            </th>

    </tr>
        
        <tr class="FilaCabecera">

            <!-- Similar al EXCEL -->

            <th style="width:10%;">
               <strong> <?php echo $this->lang->line('ejecutivo_seguimiento_col_cliente'); ?> </strong> </th>
            <th style="width:12%;">
               <strong> <?php echo $this->lang->line('ejecutivo_seguimiento_col_agencia'); ?> </strong> </th>
            <th style="width:12%;">
               <strong> <?php echo $this->lang->line('ejecutivo_seguimiento_col_oficial'); ?> </strong> </th>
            <th style="width:10%;">
               <strong> <?php echo $this->lang->line('ejecutivo_seguimiento_col_rubro'); ?> </strong> </th>
            <th style="width:15%;">
               <strong> <?php echo $this->lang->line('ejecutivo_seguimiento_col_soliciante'); ?> </strong> </th>
            <th style="width:15%;">
               <strong> <?php echo $this->lang->line('ejecutivo_seguimiento_col_actividad'); ?> </strong> </th>
            <th style="width:12%;">
               <strong> <?php echo $this->lang->line('ejecutivo_seguimiento_col_fecreg'); ?> </strong> </th>
            <th style="width:14%;">
               <strong> <?php echo $this->lang->line('ejecutivo_seguimiento_col_feccheck'); ?> </strong> </th>

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

                        <td style="text-align: center;">
                            
                            <?php
                            // 1 = Prospecto        2 = Mantenimiento
                            if($value["tipo_visita_codigo"] == 1)
                            {
                            ?>
                                <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleProspecto('<?php echo $value["codigo"]; ?>')">
                                    <?php echo PREFIJO_PROSPECTO . $value["codigo"]; ?>
                                </span>
                            <?php
                            }
                            ?>
                            
                            <?php
                            if($value["tipo_visita_codigo"] == 2)
                            {
                            ?>
                                <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleSolicitudCred('<?php echo $value["codigo"]; ?>')">
                                    <?php echo 'SOL_' . $value["codigo"]; ?>
                                </span>
                            <?php
                            }
                            ?>
                            
                        </td>

                        <td style="text-align: center;">
                            <?php echo $value["agencia_nombre"]; ?>
                        </td>
                        
                        <td style="text-align: center;">
                            <?php echo $value["ejecutivo_nombre"]; ?>
                        </td>
                        
                        <td style="text-align: center;">
                            <?php echo $value["codigo_rubro"]; ?>
                        </td>
                        
                        <td style="text-align: center;">
                            <?php echo $value["solicitante"]; ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo $value["actividad"]; ?>
                        </td>
                        
                        <td style="text-align: center;">
                            <?php echo $value["fecha_registro"]; ?>
                        </td>
                        
                        <td style="text-align: center;">
                            <?php 
                                echo $value["fecha_checkin"] . '<br><a href="https://maps.google.com/?q=' . $value["checkin_geo"] . '" target="_blank" style="font-size: 0.8em;">GEO: ' . $value["checkin_geo"] . '</a>';
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