<script type="text/javascript">
<?php
// Si no existe informaci?n, no se mostrar? como tabla con columnas con ?rden
if(count($arrRespuesta[0]) > 0)
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
    function Ajax_CargarAccion_DetalleEmpresa(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Empresa/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_DetalleProspecto(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Prospecto/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_DetalleMantenimiento(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Mantenimiento/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
</script>

<?php $cantidad_columnas = 6;?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('BandejaEjecutivoTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('BandejaEjecutivoSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <div id="divErrorBusqueda" class="mensajeBD">  </div>        
        
        <table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
            <thead>

                <tr class="FilaCabecera">

                    <th style="width:5%;">
                       N°
                    </th>

                    <!-- Similar al EXCEL -->

                    <th style="width:15%;">
                        <strong> <?php echo $this->lang->line('prospecto_id'); ?> </strong> </th>
                    <th style="width:15%;">
                        <strong> <?php echo $this->lang->line('ejecutivo_seguimiento_visita'); ?> </strong> </th>
                     <th style="width:15%;">
                        <strong> <?php echo $this->lang->line('prospecto_tipo_empresa'); ?> </strong> </th>
                     <th style="width:20%;">
                        <strong> <?php echo $this->lang->line('prospecto_nombre_empresa'); ?> </strong> </th>
                     <th style="width:15%;">
                        <strong> <?php echo $this->lang->line('ejecutivo_seguimiento_fecha_visita'); ?> </strong> </th>

                    <!-- Similar al EXCEL -->

                </tr>
            </thead>
            <tbody>
                    <?php

                    $mostrar = true;
                    if (count($arrRespuesta[0]) == 0) 
                    {
                        $mostrar = false;
                    }

                    if ($mostrar) 
                    {                
                        $i=0;
                        $strClase = "FilaBlanca";
                        foreach ($arrRespuesta as $key => $value) 
                        {                    
                            $i++;

                            ?> 
                            <tr class="<?php echo $strClase; ?>">

                                <td style="text-align: center;">
                                    <?php echo $i; ?>
                                </td>

                                <!-- Similar al EXCEL -->

                                <td style="text-align: center;">
                                    <?php
                                    // 1 = Prospecto        2 = Mantenimiento
                                    if($value["tipo_visita_codigo"] == 1)
                                    {
                                        echo '<span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleProspecto(\'' . $value["visita_id"] . '\')"> ' . PREFIJO_PROSPECTO . $value["visita_id"] . '</span>';
                                    }
                                    if($value["tipo_visita_codigo"] == 2)
                                    {
                                        echo '<span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleMantenimiento(\'' . $value["visita_id"] . '\')"> ' . PREFIJO_MANTENIMIENTO . $value["visita_id"] . '</span>';
                                    }
                                    ?>
                                </td>
                                
                                <td style="text-align: center;">

                                    <?php echo $value["tipo_visita"]; ?>

                                </td>

                                <td style="text-align: center;">
                                    <?php echo $value["empresa_categoria_detalle"]; ?>
                                </td>

                                <td style="text-align: center;">
                                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleEmpresa('<?php echo $value["empresa_id"]; ?>')">
                                        <?php echo $value["empresa_nombre"]; ?>
                                    </span>
                                </td>

                                <td style="text-align: center;">
                                    <?php echo $value["cal_visita_ini"]; ?>
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
    </div>
</div>