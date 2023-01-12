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
        Ajax_CargadoGeneralPagina('Documento/Registro', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
	
	function Visor_Documento(documento_base64) {
		
        var r = Math.random().toString(36).substr(2,16);
        var type = 'dto';
        window.open('Documento/Visualizar?' + r + '&type=' + type + '&documento_base64=' + documento_base64, '_blank');
		
    }
	
    
</script>

<?php $cantidad_columnas = 4; ?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('DocumentoTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('DocumentoSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <div align="left" class="BotonesVariasOpciones">

            <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Documento/Registro')">
                <?php echo $this->lang->line('TablaOpciones_NuevoCatalogo'); ?>
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

                                <th style="width:35%;">
                                   <?php echo $this->lang->line('documento_nombre'); ?> </th>
                                <th style="width:25%;">
                                   <?php echo $this->lang->line('documento_enviar'); ?> </th>

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
                                                    <?php echo $value["documento_nombre"]; ?>
                                            </td>
                                            <td style="text-align: center;">
                                                    <?php echo $value["documento_enviar_detalle"]; ?>
                                            </td>

                                            <!-- Similar al EXCEL -->

                                            <td style="text-align: center;">

                                                <?php

                                                if($value["documento_enviar_codigo"] == 1 && $value["documento_enlace"] != false)
                                                {
                                                ?>	
                                                        <span class="BotonSimple" onclick="Visor_Documento('<?php echo $value["documento_pdf"]; ?>')">
                                                                <?php echo $this->lang->line('TablaOpciones_VerDocumento'); ?>
                                                        </span>
                                                <?php
                                                }
                                                ?>

                                                <span class="BotonSimple" onclick="Ajax_CargarAccion_Editar('<?php echo $value["documento_id"]; ?>')">
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