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
    function Ajax_CargarAccion_BandejaSolicitud(estado) {
        var strParametros = "&estado=" + estado + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Solicitud/Mantenimiento/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_DetalleMantenimiento(codigo, estado) {
        var strParametros = "&codigo=" + codigo + "&estado=" + estado + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Solicitud/Mantenimiento/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    <?php
    if($estado == 0)
    {
    ?>    
        function Ajax_CargarAccion_RechazarMantenimiento(codigo, estado) {
            var strParametros = "&codigo=" + codigo + "&estado=" + estado + "&tipo_accion=1";
            Ajax_CargadoGeneralPagina('Mantenimiento/Rechazar/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
        }
        
        function Ajax_CargarAccion_AprobarMantenimiento(codigo, estado) {
            var strParametros = "&codigo=" + codigo + "&estado=" + estado + "&tipo_accion=1";
            Ajax_CargadoGeneralPagina('Mantenimiento/Aprobar/Registro', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
        }
    
    <?php
    }                                                       
    ?>
    
</script>

<?php $cantidad_columnas = 5;?>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('MantenimientoTitulo') . ' - ' . $estado_texto . 's'; ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('MantenimientoSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <div align="left" class="BotonesVariasOpciones">
            
            <span class="BotonMinimalista" onclick="Ajax_CargarAccion_BandejaSolicitud(0)">
                <?php echo $this->lang->line('solicitud_estado_pendiente'); ?> 
            </span>
        </div>
        
        <div align="left" class="BotonesVariasOpciones">
            <span class="BotonMinimalista" onclick="Ajax_CargarAccion_BandejaSolicitud(1)">
                <?php echo $this->lang->line('solicitud_estado_aprobado'); ?>
            </span>
        </div>
        
        <div align="left" class="BotonesVariasOpciones">
            <span class="BotonMinimalista" onclick="Ajax_CargarAccion_BandejaSolicitud(2)">
                <?php echo $this->lang->line('solicitud_estado_cancelado'); ?>
            </span>
        </div>
        
        <div style="clear: both"></div>
        
		<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
                    <thead>

                        <tr class="FilaCabecera">

                            <th style="width:5%;">
                               N°
                            </th>

                            <!-- Similar al EXCEL -->

                            <th style="width:25%;">
                               <?php echo $this->lang->line('solicitud_nombre_empresa'); ?> </th>
                            <th style="width:20%;">
                               <?php echo $this->lang->line('empresa_nit'); ?> </th>
                            <th style="width:15%;">
                               <?php echo $this->lang->line('solicitud_fecha'); ?> </th>

                            <!-- Similar al EXCEL -->

                            <th style="width:35%;"> <?php echo $this->lang->line('TablaOpciones'); ?> </th>
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
                                            <?php echo $value["solicitud_nombre_empresa"]; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $value["solicitud_nit"]; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php echo $value["solicitud_fecha"]; ?>
                                        </td>

                                        <!-- Similar al EXCEL -->

                                        <td style="text-align: center;">

                                            <span class="BotonSimple" onclick="Ajax_CargarAccion_DetalleMantenimiento('<?php echo $value["solicitud_id"]; ?>', <?php echo $estado; ?>)">
                                                    <?php echo $this->lang->line('TablaOpciones_Detalle'); ?>
                                            </span>

                                            <?php

                                            if($estado == 0)
                                            {                                                        
                                            ?>

                                                <span class="BotonSimple" onclick="Ajax_CargarAccion_RechazarMantenimiento('<?php echo $value["solicitud_id"]; ?>', <?php echo $estado; ?>)">
                                                        <?php echo $this->lang->line('TablaOpciones_Rechazar'); ?>
                                                </span>
                                            
                                                <span class="BotonSimple" onclick="Ajax_CargarAccion_AprobarMantenimiento('<?php echo $value["solicitud_id"]; ?>', <?php echo $estado; ?>)">
                                                        <?php echo $this->lang->line('TablaOpciones_aceptar_solicitud'); ?>
                                                </span>

                                            <?php
                                            }                                                    
                                            ?>

                                        </td>

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
                                <?php 
                                
                                    if($estado == 0)
                                    {
                                        echo $this->lang->line('TablaNoPendientes');
                                    }
                                    else
                                    {
                                        echo $this->lang->line('TablaNoRegistros'); 
                                    }
                                ?>
                                <br><br>
                            </td>
                        </tr>
                    <?php } ?>
		</table>
		
		<div id="divErrorBusqueda" class="mensajeBD">

        </div>

    </div>
</div>