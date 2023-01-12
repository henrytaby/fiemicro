
<?php 
	$cantidad_columnas = 6; 
	
    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=ReporteAuditoriaAccesos" . date('Y-m-d_H-i-s').".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
	
?>

<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
	<thead>
		<tr class="FilaCabecera">
		
			<th colspan="<?php echo $cantidad_columnas; ?>">
				<?php echo $this->lang->line('NombreSistema'); ?>
				<br />
				<?php echo $this->lang->line('auditoria_Reporte'); ?>
				<br />
				<?php echo 'Generado por: ' . $_SESSION["session_informacion"]["login"]; ?>
				<br />
				<?php echo 'En fecha: ' . date('d/m/Y H:i:s'); ?>
			</th>
		
		</tr>
		
		<tr class="FilaCabecera">
		
			<th style="text-align: left !important;" colspan="<?php echo $cantidad_columnas; ?>">
				FILTRO: <?php echo $filtro_texto; ?>
			</th>
		
		</tr>
	
		<tr class="FilaCabecera">

			<th style="width:10%;">
			   N° 
			</th>
			
			<!-- Similar al EXCEL -->
			
			<th style="width:20%;">
			   <?php echo $this->lang->line('auditoria_usuario'); ?>       </th>
			<th style="width:25%;">
				<?php echo $this->lang->line('auditoria_tipo_acceso'); ?>   </th>
			<th style="width:25%;">
				<?php echo $this->lang->line('auditoria_fecha'); ?>   </th>
                        <th style="width:20%;">
				<?php echo $this->lang->line('auditoria_tipo_ip'); ?>   </th>
		   
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
						<?php echo $value["audit_usuario"]; ?>
					</td>                            
					<td style="text-align: center;">
						<?php echo $value["audit_tipo_acceso"]; ?>
					</td>                            
					<td style="text-align: center;">
						<?php echo $value["audit_fecha"]; ?>
					</td>
					<td style="text-align: center;">
						<?php echo $value["audit_ip"]; ?>
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