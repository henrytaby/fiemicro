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
    function Ajax_CargarAccion_Editar(codigo) {
        var strParametros = "&codigo=" + codigo + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Campana/Registro', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
</script>

<?php $cantidad_columnas = 9;?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('CampanaTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('CampanaSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
		<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 90%;">
			<thead>
			
				<tr class="FilaCabecera">

					<th style="width:5%;">
					   N°
					</th>
					
					<!-- Similar al EXCEL -->
					
                                        <th style="width:10%;">
					   <?php echo $this->lang->line('campana_tipo'); ?> </th>
                                        <th style="width:10%;">
					   <?php echo $this->lang->line('estructura_nombre'); ?> </th>
                                        <th style="width:12%;">
					   <?php echo $this->lang->line('campana_desc'); ?> </th>
                                        <th style="width:10%;">
					   <?php echo $this->lang->line('campana_monto_oferta'); ?> </th>
                                        <th style="width:10%;">
					   <?php echo $this->lang->line('campana_tasa'); ?> </th>
                                        <th style="width:10%;">
					   <?php echo $this->lang->line('campana_fecha_inicio'); ?> </th>
                                        <th style="width:8%;">
					   <?php echo $this->lang->line('campana_plazo'); ?> </th>
                                        <th style="width:10%;">
					   <?php echo $this->lang->line('campana_servicios'); ?> </th>
				   
					<!-- Similar al EXCEL -->
					
					<th style="width:15%;"> <?php echo $this->lang->line('TablaOpciones'); ?> </th>
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
                                                    <?php echo $value["camtip_nombre"]; ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                    <?php echo $value["camp_nombre"]; ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                    <?php echo $value["camp_desc"]; ?>
                                            </td>
                                            
                                            <td style="text-align: right;">
                                                    <?php echo $value["camp_monto_oferta"]; ?>
                                            </td>
                                            
                                            <td style="text-align: right;">
                                                    <?php echo $value["camp_tasa"]; ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                    <?php echo $value["camp_fecha_inicio"]; ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                    <?php echo $value["camp_plazo"]; ?>
                                            </td>
                                            
                                            <td style="text-align: justify;">
                                                    <?php
                                                    
                                                    // Listado de Servicios
                                                    echo $this->mfunciones_generales->GetValorCatalogo($value["camp_id"], 'campana_productos');
                                                    
                                                    ?>
                                            </td>

                                            <!-- Similar al EXCEL -->

                                            <td style="text-align: center;">

                                                    <span class="BotonSimple" onclick="Ajax_CargarAccion_Editar('<?php echo $value["camp_id"]; ?>')">
                                                            <?php echo $this->lang->line('TablaOpciones_Editar'); ?>
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
		
		<div id="divErrorBusqueda" class="mensajeBD">

        </div>

    </div>
</div>