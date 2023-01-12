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
    
    function Ajax_CargarAccion_DocumentoProspecto(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Prospecto/Documento/Ver', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
</script>

<?php $cantidad_columnas = 7;?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('SupervisorAgenciaTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('SupervisorAgenciaSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <div id="divErrorBusqueda" class="mensajeBD">  </div>
        
		<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
			<thead>
			
                            <tr class="FilaCabecera">

                                <th style="width:5%;">
                                   N°
                                </th>

                                <!-- Similar al EXCEL -->

                                <th style="width:5%;">
                                   <?php echo $this->lang->line('prospecto_codigo'); ?> </th>
                                <th style="width:15%;">
                                   <?php echo $this->lang->line('prospecto_tipo_persona'); ?> </th>
                                <th style="width:20%;">
                                   <?php echo $this->lang->line('prospecto_nombre_empresa'); ?> </th>
                                <th style="width:10%;">
                                   <?php echo $this->lang->line('prospecto_fecha_asignaccion'); ?> </th>

                                <!-- Similar al EXCEL -->
                                
                                <th style="width:45%;"> <?php echo $this->lang->line('TablaOpciones'); ?> </th>

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
                                                <?php echo PREFIJO_PROSPECTO . $value["prospecto_id"]; ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value["tipo_persona_detalle"]; ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleEmpresa('<?php echo $value["empresa_id"]; ?>')">
                                                    
                                                    <?php
                                                    if($value["empresa_categoria_codigo"] == 1)
                                                    {
                                                        echo IMAGEN_COMERCIO;
                                                    }
                                                    else
                                                    {
                                                        echo IMAGEN_ESTABLECIMIENTO;
                                                    }                                                
                                                    ?>
                                                    
                                                    <?php echo $value["empresa_nombre_legal"]; ?>
                                                </span>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value["prospecto_fecha_asignacion"]; ?>
                                            </td>

                                            <!-- Similar al EXCEL -->

                                            <td style="text-align: center;">
                                                
                                                <span class="BotonSimple" onclick="Ajax_CargarAccion_DocumentoProspecto('<?php echo $value["prospecto_id"]; ?>')">
                                                        <?php echo $this->lang->line('TablaOpciones_revisar_documentacion'); ?>
                                                </span>
                                                
                                                <span class="BotonSimple" onclick="Ajax_CargadoGeneralPagina('Mantenimiento/Mantenimiento', 'divElementoFlotante', 'divErrorListaResultado');">
                                                        <?php echo $this->lang->line('TablaOpciones_observar_devolver'); ?>
                                                </span>
                                                
                                                <span class="BotonSimple" onclick="Ajax_CargadoGeneralPagina('Mantenimiento/Mantenimiento', 'divElementoFlotante', 'divErrorListaResultado');">
                                                        <?php echo $this->lang->line('TablaOpciones_autorizar_documentacion'); ?>
                                                </span>                                                

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
                                        <?php echo $this->lang->line('TablaNoRegistros'); ?>
                                        <br><br>
                                    </td>                               
				</tr>
			<?php } ?>            
		</table>                
    </div>
</div>