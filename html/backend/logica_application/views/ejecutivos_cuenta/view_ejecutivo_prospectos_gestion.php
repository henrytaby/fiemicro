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
    function Ajax_CargarAccion_GestionLead(codigo, codigo_ejecutivo, tipo_registro) {
        var strParametros = "&codigo=" + codigo + "&codigo_ejecutivo=" + codigo_ejecutivo + "&tipo_registro=" + tipo_registro;
        Ajax_CargadoGeneralPagina('Ejecutivo/Prospecto/Gestion', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
</script>

<?php $cantidad_columnas = 9;?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br />
        
        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
        
            <div class="FormularioSubtitulo"> Gestión de Registros Global<?php echo $region_nombres; ?></div>
            <div class="FormularioSubtituloComentarioNormal ">En este apartado puede gestionar la información de los registros. Considere que los cambios realizados puede afectar directamente al registro y causar des-actualización en ciertas etapas. </div>
        
        <div style="clear: both"></div>
            
            <div class="mensaje_alerta" style="text-align: center !important;"> <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Realice la gestión de la información de los registros sólo si es estricamente necesario. Todas las acciones se guardarán en los Logs del Sistema. </div> <br />
            
        <div style="clear: both"></div>
        
            <div style="text-align: right !important; margin-left: 8%;">

                <?php
            
                    $direccion_bandeja = 'Menu/Principal';

                    if(isset($_SESSION['direccion_bandeja_actual']))
                    {
                        $direccion_bandeja = $_SESSION['direccion_bandeja_actual'];
                    }

                ?>
                
                <span class="EnlaceSimple" onclick="Ajax_CargarOpcionMenu('<?php echo $direccion_bandeja; ?>');" style="padding-right: 20px;">
                    <strong> <i class="fa fa-refresh" aria-hidden="true"></i> Actualizar Bandeja </strong>
                </span>
                
            </div>
        
        <div id="divErrorBusqueda" class="mensajeBD">  </div>
        
		<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
			<thead>
			
                            <tr class="FilaCabecera">

                                <th style="width:5%;">
                                   N°
                                </th>

                                <!-- Similar al EXCEL -->

                                <th style="width:10%;"> <?php echo $this->lang->line('prospecto_codigo'); ?> </th>
                                <th style="width:10%;"> Rubro </th>
                                <th style="width:20%;"> Solicitante </th>
                                <th style="width:15%;"> <?php echo $this->lang->line('general_ci'); ?> </th>
                                <th style="width:15%;"> <?php echo $this->lang->line('prospecto_fecha_asignaccion'); ?> </th>
                                <th style="width:10%;"> Consolidado </th>

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
                                                
                                               <?php echo PREFIJO_PROSPECTO . $value["prospecto_id"]; ?>
                                                
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value["camp_nombre"]; ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value["general_solicitante"]; ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value['general_ci'] . " " . ($value['onboarding'] == 0 ? $this->mfunciones_generales->GetValorCatalogo($value['general_ci_extension'], 'extension_ci') : ''); ?> </span>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value["prospecto_fecha_asignacion"]; ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value["prospecto_consolidado"]; ?>
                                            </td>
                                            
                                            <!-- Similar al EXCEL -->
                                            
                                            <td style="text-align: center;">
                                                <span class="BotonSimple" onclick="Ajax_CargarAccion_GestionLead('<?php echo $value["prospecto_id"]; ?>', '<?php echo $value["ejecutivo_id"]; ?>', 'unidad_familiar')">
                                                    <?php echo $this->lang->line('TablaOpciones_gestion_lead'); ?>
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
		
		<div id="divErrorBusqueda" class="mensajeBD">  </div>

                <?php
                
                if($_SESSION['flag_bandeja_agente'] == 0)
                {
                ?>
                
                    <div class="Botones2Opciones">
                        <a onclick="Ajax_CargarOpcionMenu('Ejecutivo/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
                    </div>
                
                <?php
                }
                ?>
                
    </div>
</div>