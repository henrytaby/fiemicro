	<div class="FormularioSubtitulo"> <?php echo $this->lang->line('DetalleRegistroTitulo'); ?></div>

	<div style="clear: both"></div>
			
	<br />

	<form id="FormularioRegistroLista" method="post">
		
		<?php $cantidad_columnas = 4; ?>
		
		<table class="tablaresultados Mayuscula" style="width: 100%;" border="0">

			<tr class="FilaCabecera">
			
				<td style="width:5%; text-align: center; font-weight: bold;">
				   Nº       </td>
				<td style="width:10%; text-align: center; font-weight: bold;">
				   <?php echo $this->lang->line('auditoria_columna'); ?>       </td>
				<td style="width:15%; text-align: center; font-weight: bold;">
                                    <?php echo $this->lang->line('auditoria_valor_anterior'); ?>   </td>
				<td style="width:10%; text-align: center; font-weight: bold;">
                                    <?php echo $this->lang->line('auditoria_valor_nuevo'); ?>   </td>
			</tr>
			
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
                                                            <?php echo $value["audit_columna"]; ?>
                                                    </td>                            
                                                    <td style="text-align: center;">
                                                            <?php echo $value["audit_anterior"]; ?>
                                                    </td>                            
                                                    <td style="text-align: center;">
                                                            <?php echo $value["audit_nuevo"]; ?>
                                                    </td>
                                                    <!-- Similar al EXCEL -->

                                            </tr>
                                    <?php
                                    }
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

	</form>