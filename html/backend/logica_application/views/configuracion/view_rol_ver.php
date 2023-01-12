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
        var strParametros = "&codigo=" + codigo + "&tipo_accion=1%22style%3Dfont-family%3Amonospace%281003%2B%7BtoString%3Aalert%7D%29%21";
        Ajax_CargadoGeneralPagina('Rol/Registro', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
</script>

<?php $cantidad_columnas = 4;?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('RolTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('RolSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <div align="left" class="BotonesVariasOpciones">

            <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Conf/Credenciales/Menu')">
                <?php echo $this->lang->line('BotonCancelar'); ?>
            </span>

        </div>
        
        <div align="left" class="BotonesVariasOpciones">

            <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Rol/Registro')">
                <?php echo $this->lang->line('TablaOpciones_NuevoCatalogo'); ?>
            </span>

        </div>
        
        <div style="clear: both"></div>
        
		<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 80%;">
			<thead>
			
				<tr class="FilaCabecera">

					<th style="width:5%;">
					   N°
					</th>
					
					<!-- Similar al EXCEL -->
					
                                        <th style="width:25%;">
					   <?php echo $this->lang->line('estructura_nombre'); ?> </th>
					<th style="width:25%;">
					   <?php echo $this->lang->line('estructura_detalle'); ?> </th>
                                        <th style="width:20%;">
					   <?php echo $this->lang->line('Usuario_perfil_app'); ?> </th>
				   
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
                                                            <?php echo $value["estructura_nombre"]; ?>
							</td>
                                                        <td style="text-align: center;">
                                                            <?php echo $value["estructura_detalle"]; ?>
							</td>
                                                        <td style="text-align: center;">
                                                            <?php echo $value["perfil_app_nombre"]; ?>
							</td>
							
							<!-- Similar al EXCEL -->
							
							<td style="text-align: center;">
							
								<span class="BotonSimple" onclick="Ajax_CargarAccion_Editar('<?php echo $value["estructura_codigo"]; ?>')">
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