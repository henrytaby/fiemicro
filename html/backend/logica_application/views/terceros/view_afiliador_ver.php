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
        Ajax_CargadoGeneralPagina('Afiliador/Registro/Registro', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_EnviarCorreo(codigo) {
        var strParametros = "&codigo=" + codigo + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Afiliador/Envio/Ver', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
</script>

<?php $cantidad_columnas = 9;?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('AfiliadorTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('AfiliadorSubtitulo') . $this->lang->line('AfiliadorSubtitulo2'); ?></div>
        
        <div style="clear: both"></div>
        
        <div align="left" class="BotonesVariasOpciones">

            <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Afiliador/Registro/Registro')">
                <?php echo $this->lang->line('TablaOpciones_NuevoCatalogo'); ?>
            </span>

        </div>
        
        <div style="clear: both"></div>
        
		<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
			<thead>
			
				<tr class="FilaCabecera">
					
					<!-- Similar al EXCEL -->
					
                                        <th style="width:15%;">
					   <?php echo $this->lang->line('afiliador_nombre'); ?> </th>
                                        <th style="width:15%;">
					   <?php echo $this->lang->line('afiliador_key'); ?> </th>
                                        <th style="width:15%;">
					   <?php echo $this->lang->line('afiliador_responsable_nombre'); ?> </th>
                                        <th style="width:10%;">
					   <?php echo $this->lang->line('afiliador_responsable_email'); ?> </th>
                                        <th style="width:10%;">
					   <?php echo $this->lang->line('afiliador_responsable_contacto'); ?> </th>
                                        <th style="width:10%;">
					   <?php echo $this->lang->line('afiliador_referencia_documento'); ?> </th>
                                        <th style="width:10%;">
					   <?php echo $this->lang->line('afiliador_fecha_registro'); ?> </th>
				   
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

                                            <!-- Similar al EXCEL -->

                                            <td style="text-align: center;">
                                                <?php echo $value["afiliador_nombre"]; ?>
                                            </td>
                                            
                                            <td style="text-align: center; font-size: 7px !important;">
                                                <?php echo $value["afiliador_key"]; ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value["afiliador_responsable_nombre"]; ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value["afiliador_responsable_email"]; ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value["afiliador_responsable_contacto"]; ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value["afiliador_referencia_documento"]; ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value["afiliador_fecha_registro"]; ?>
                                            </td>

                                            <!-- Similar al EXCEL -->

                                            <td style="text-align: center;">

                                                    <span class="BotonSimple" onclick="Ajax_CargarAccion_Editar('<?php echo $value["afiliador_id"]; ?>')">
                                                            <?php echo $this->lang->line('TablaOpciones_Editar'); ?>
                                                    </span>
                                                
                                                    <span class="BotonSimple" onclick="Ajax_CargarAccion_EnviarCorreo('<?php echo $value["afiliador_id"]; ?>')">
                                                            <?php echo $this->lang->line('TablaOpciones_enviar_correo'); ?>
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