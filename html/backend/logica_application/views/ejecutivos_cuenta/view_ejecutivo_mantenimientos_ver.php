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
    
    function Ajax_CargarAccion_DetalleMantenimiento(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Mantenimiento/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
</script>

<?php $cantidad_columnas = 7;?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtitulo"> <?php echo $this->lang->line('TablaOpciones_mantenimientos_asignados_titulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <br /><br />
        
		<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
			<thead>
			
                            <tr class="FilaCabecera">

                                <th style="width:5%;">
                                   N°
                                </th>

                                <!-- Similar al EXCEL -->

                                <th style="width:5%;">
                                   <?php echo $this->lang->line('prospecto_codigo'); ?> </th>
                                <th style="width:30%;">
                                   <?php echo $this->lang->line('prospecto_tipo_empresa'); ?> </th>
                                <th style="width:40%;">
                                   <?php echo $this->lang->line('prospecto_nombre_empresa'); ?> </th>
                                <th style="width:10%;">
                                   <?php echo $this->lang->line('prospecto_fecha_asignaccion'); ?> </th>
                                <th style="width:10%;">
                                   <?php echo $this->lang->line('prospecto_estado_consolidado'); ?> </th>

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
                                                <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleMantenimiento('<?php echo $value["mantenimiento_id"]; ?>')">
                                                    <?php echo PREFIJO_MANTENIMIENTO . $value["mantenimiento_id"]; ?>
                                                </span>                                                
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value["empresa_categoria_detalle"]; ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleEmpresa('<?php echo $value["empresa_id"]; ?>')">
                                                    <?php echo $value["empresa_nombre_legal"]; ?>
                                                </span>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value["mantenimiento_fecha_asignacion"]; ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value["mantenimiento_estado_detalle"]; ?>
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
		
		<div id="divErrorBusqueda" class="mensajeBD"> </div>
                
            <div class="Botones2Opciones">
                <a onclick="Ajax_CargarOpcionMenu('Ejecutivo/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
            </div>

    </div>
</div>