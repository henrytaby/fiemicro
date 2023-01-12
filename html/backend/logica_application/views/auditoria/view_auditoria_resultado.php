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
    
    function Ajax_CargarAccion_DetalleUsuario(tipo_codigo, codigo_usuario) {
        var strParametros = "&tipo_codigo=" + tipo_codigo + "&codigo_usuario=" + codigo_usuario;
        Ajax_CargadoGeneralPagina('Usuario/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_Detalle(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Auditoria/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
</script>

<?php $cantidad_columnas = 7;?>

<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
	<thead>
		<tr class="FilaCabecera">
		
			<th  style="text-align: left !important;" colspan="<?php echo $cantidad_columnas; ?>">
			   
				<div class="BotonExportar" onclick="window.open('Auditoria/Excel', '_blank');">
					<span><?php echo $this->lang->line('exportar'); ?></span><img src="html_public/imagenes/descenso_diminuto.png" style="vertical-align: middle;" />
				</div>
			   
			   FILTRO: <?php echo $filtro_texto; ?>
			</th>
		
		</tr>
	
		<tr class="FilaCabecera">

			<th style="width:5%;">
			   N°
			</th>
			
			<!-- Similar al EXCEL -->
			
			<th style="width:10%;">
			   <?php echo $this->lang->line('auditoria_tabla_corta'); ?>       </th>
			<th style="width:15%;">
				<?php echo $this->lang->line('auditoria_accion'); ?>   </th>
			<th style="width:10%;">
				<?php echo $this->lang->line('auditoria_pk'); ?>   </th>
			<th style="width:10%;">
				<?php echo $this->lang->line('auditoria_usuario'); ?>   </th>
			<th style="width:10%;">
				<?php echo $this->lang->line('auditoria_fecha'); ?>   </th>
		   
			<!-- Similar al EXCEL -->
			
			<th style="width:25%;"> <?php echo $this->lang->line('TablaOpciones'); ?> </th>
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
						<?php echo $value["audit_tabla"]; ?>
					</td>                            
					<td style="text-align: center;">
						<?php echo $value["audit_accion"]; ?>
					</td>                            
					<td style="text-align: center;">
						<?php echo $value["audit_pk1"]; ?>
					</td>
					<td style="text-align: center;">
						<span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleUsuario(1, '<?php echo $value["audit_usuario"]; ?>')">
                                                    <?php echo $value["audit_usuario"]; ?>
                                                </span>
					</td>
					<td style="text-align: center;">
						<?php echo $value["audit_fecha"]; ?>
					</td>
					
					<!-- Similar al EXCEL -->
					
					<td style="text-align: center;">
					
						<span class="BotonSimple" onclick="Ajax_CargarAccion_Detalle('<?php echo $value["audit_id"]; ?>')">
							<?php echo $this->lang->line('TablaOpciones_Detalle'); ?>
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