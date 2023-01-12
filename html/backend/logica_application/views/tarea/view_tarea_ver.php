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
        Ajax_CargadoGeneralPagina('Tarea/Registro', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
</script>

<?php $cantidad_columnas = 5;?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('TareaTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('TareaSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <div align="left" class="BotonesVariasOpciones">

            <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Tarea/Registro')">
                <?php echo $this->lang->line('TablaOpciones_NuevoCatalogo'); ?>
            </span>

        </div>
        
        <div style="clear: both"></div>
        
		<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 80%;">
			<thead>
			
				<tr class="FilaCabecera">

					<th style="width:10%;">
					   N°
					</th>
					
					<!-- Similar al EXCEL -->
					
                                        <th style="width:35%;">
					   <?php echo $this->lang->line('estructura_nombre'); ?> </th>
                                        <th style="width:20%;">
					   <?php echo $this->lang->line('consulta_perfil_app'); ?> </th>
                                        <th style="width:15%;">
					   <?php echo $this->lang->line('Usuario_activo'); ?> </th>
				   
					<!-- Similar al EXCEL -->
					
					<th style="width:20%;"> <?php echo $this->lang->line('TablaOpciones'); ?> </th>
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
                                                    echo $value["estructura_nombre"]; 

                                                    if($value["estructura_codigo"] == 1)
                                                    {
                                                        echo " (Digitalización Evidencia)";
                                                    }

                                                ?>
                                            </td>

                                            <td style="text-align: center;">
                                                <?php
                                                
                                                if($value["estructura_codigo"] == 1)
                                                {
                                                    echo "Todos";
                                                }
                                                else
                                                {
                                                    echo $value["perfil_app_nombre"]; 
                                                }
                                                
                                                ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value["tarea_activo"]; ?>
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