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
        Ajax_CargadoGeneralPagina('Ejecutivo/Registro', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_DetalleUsuario(tipo_codigo, codigo_usuario) {
        var strParametros = "&tipo_codigo=" + tipo_codigo + "&codigo_usuario=" + codigo_usuario;
        Ajax_CargadoGeneralPagina('Usuario/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_Prospecto(codigo) {
        var strParametros = "&codigo=" + codigo + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Ejecutivo/Prospecto/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_Mantenimiento(codigo) {
        var strParametros = "&codigo=" + codigo + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Ejecutivo/Mantenimiento/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_Zona(codigo) {
        var strParametros = "&codigo=" + codigo + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Ejecutivo/Zona/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_Horario(codigo) {
        var strParametros = "&codigo=" + codigo + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Ejecutivo/Horario', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_VerHorario(codigo) {
        var strParametros = "&codigo=" + codigo + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Ejecutivo/Lectura/Horario', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_ForGestion() {
        var strParametros = "&codigo=" + Math.random().toString(36).substr(2,16);
        Ajax_CargadoGeneralPagina('Ejecutivo/Prospecto/ForGestion', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_ActualizarPerfilTipo(codigo_ejecutivo, codigo_perfil) {
        
        var cnfrm = confirm('Marcar Oficial de Negocios como ' + (codigo_perfil=='2' ? '<?php echo $this->lang->line('ejecutivo_perfil_tipo_catb'); ?>' : '<?php echo $this->lang->line('ejecutivo_perfil_tipo_generico'); ?>') + '\n¿Confirma que quiere continuar?');
        if(cnfrm != true)
        {
            return false;
        }
        else
        {
            var strParametros = "&codigo_ejecutivo=" + codigo_ejecutivo + "&codigo_perfil=" + codigo_perfil;
            Ajax_CargadoGeneralPagina('Ejecutivo/PerfilTipo/Actualizar', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
        }
    }
    
</script>

<?php

    $cantidad_columnas = 5;
    
    $nombre_bandeja = $this->mfunciones_generales->GetValorCatalogo($_SESSION["identificador_tipo_perfil_app"], 'tipo_perfil_app');
    $nombre_columna = $this->mfunciones_generales->GetValorCatalogo($_SESSION["identificador_tipo_perfil_app"], 'tipo_perfil_app_singular');

?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>
                
            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('EjecutivoTitulo') . $nombre_bandeja . $nombre_region; ?></div>
            <div class="FormularioSubtituloComentarioNormal ">
                <?php 
                    echo sprintf($this->lang->line('EjecutivoSubtitulo'), $nombre_bandeja, $nombre_bandeja); 
                    
                    echo ((int)$_SESSION["identificador_tipo_perfil_app"]==1 ? 'Como medida adicional, puede forzar la gestión de los registros <span onclick="Ajax_CargarAccion_ForGestion()" style="cursor: pointer; text-decoration: underline;"> desde aquí</span>. ' : '');
                ?>
            </div>
        
        <div style="clear: both"></div>
        
        <br />
        
        <div align="left" class="BotonesVariasOpciones">

            <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Ejecutivo/Registro')">
                <?php echo $this->lang->line('TablaOpciones_habilitar_ejecutivo'); ?>
            </span>

        </div>
        
        <div align="left" class="BotonesVariasOpciones">

            <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Ejecutivo/Mapa/Ver')">
                <?php echo $this->lang->line('TablaOpciones_mapa_ejecutivo') . $nombre_bandeja; ?>
            </span>

        </div>
        
        <?php
        if((int)$_SESSION["identificador_tipo_perfil_app"] == 1)
        {
        ?>
            <div align="left" class="BotonesVariasOpciones">

                <span class="BotonMinimalista" onclick="Ajax_CargarOpcionMenu('Ejecutivo/Metrica/Ver')">
                    <?php echo $this->lang->line('TablaOpciones_ejecutivo_indice'); ?>
                </span>

            </div>
        <?php
        }
        ?>
        
        <div style="clear: both"></div>
        
        <div id="divErrorBusqueda" class="mensajeBD"> </div>
        
		<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
			<thead>
			
                            <tr class="FilaCabecera">

                                <th style="width:5%;">
                                   N°
                                </th>

                                <!-- Similar al EXCEL -->

                                <th style="width:10%;">
                                   <?php echo str_replace('/' , '/ ' ,$nombre_columna); ?> </th>
                                <th style="width:20%;">
                                   <?php echo 'Nombre del ' . str_replace('/' , '/ ' ,$nombre_columna); ?> </th>
                                <th style="width:10%;">
                                   <?php echo $this->lang->line('ejecutivo_zona'); ?> </th>

                                <!-- Similar al EXCEL -->

                                <th style="width:55%;"> <?php echo $this->lang->line('TablaOpciones'); ?> </th>
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
                                                
                                                    switch ((int)$_SESSION["identificador_tipo_perfil_app"]) {
                                                        case 1:
                                                            
                                                            echo PREFIJO_EJECUTIVO;
                                                            
                                                            break;
                                                        
                                                        case 3:
                                                            
                                                            echo 'NORM_';
                                                            
                                                            break;

                                                        default:
                                                            break;
                                                    }
                                                            
                                                    echo $value["ejecutivo_id"];
                                                    
                                                    if((int)$_SESSION["identificador_tipo_perfil_app"] == 1 && $value["ejecutivo_perfil_tipo"] == 2)
                                                    {
                                                        $texto_perfil = $this->lang->line('ejecutivo_perfil_tipo_catb');
                                                        
                                                        if($this->mfunciones_microcreditos->CheckIsMobile())
                                                        {
                                                            $texto_perfil = str_replace(' ', '', $texto_perfil);
                                                        }
                                                        
                                                        echo '<br /><span style="background-color: #006699; color: #ffffff; padding: 2px; border-radius: 5px; line-height: 20px;" title="Perfil del Oficial de Negocios">' . $texto_perfil . '</span>';
                                                    }
                                                    
                                                ?>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                
                                                <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleUsuario(0, '<?php echo $value["usuario_id"]; ?>')">
                                                    <?php echo $value["usuario_nombre"]; ?>
                                                </span>
                                            </td>
                                            
                                            <td style="text-align: center;">
                                                <?php echo $value["zona_registrada"]; ?>
                                            </td>

                                            <!-- Similar al EXCEL -->

                                            <td style="text-align: center;">
                                                
                                                <span class="BotonSimple" onclick="Ajax_CargarAccion_Zona('<?php echo $value["ejecutivo_id"]; ?>')">
                                                        <?php echo $this->lang->line('TablaOpciones_asignar_zona'); ?>
                                                </span>
                                                
                                                <span class="BotonSimple" onclick="Ajax_CargarAccion_Horario('<?php echo $value["ejecutivo_id"]; ?>')">
                                                        <?php echo $this->lang->line('TablaOpciones_horario'); ?>
                                                </span>
                                                
                                                <span class="BotonSimple" onclick="Ajax_CargarAccion_Prospecto('<?php echo $value["ejecutivo_id"]; ?>')">
                                                        <?php echo $this->lang->line('TablaOpciones_prospectos_asignados'); ?>
                                                </span>
                                                
                                                <?php
                                                if((int)$_SESSION["identificador_tipo_perfil_app"] == 1)
                                                {
                                                ?>
                                                    <span class="BotonSimple" onclick="Ajax_CargarAccion_Mantenimiento('<?php echo $value["ejecutivo_id"]; ?>')">
                                                            <?php echo $this->lang->line('TablaOpciones_mantenimientos_asignados'); ?>
                                                    </span>
                                                <?php
                                                }
                                                ?>
                                                
                                                <span class="BotonSimple" onclick="Ajax_CargarAccion_Editar('<?php echo $value["ejecutivo_id"]; ?>')">
                                                        <?php echo $this->lang->line('TablaOpciones_transferir'); ?>
                                                </span>
                                                
                                                <span class="BotonSimple" onclick="Ajax_CargarAccion_VerHorario('<?php echo $value["ejecutivo_id"]; ?>')">
                                                        <?php echo $this->lang->line('TablaOpciones_ver_horario'); ?>
                                                </span>
                                                
                                                <?php
                                                if((int)$_SESSION["identificador_tipo_perfil_app"] == 1)
                                                {
                                                ?>
                                                    <span class="BotonSimple" onclick="Ajax_CargarAccion_ActualizarPerfilTipo('<?php echo $value["ejecutivo_id"]; ?>', '<?php echo ($value["ejecutivo_perfil_tipo"]==1 ? 2 : 1); ?>')">
                                                            <?php echo 'Marcar<br />' . $this->lang->line(($value["ejecutivo_perfil_tipo"]==1 ? 'ejecutivo_perfil_tipo_catb': 'ejecutivo_perfil_tipo_generico')); ?>
                                                    </span>
                                                <?php
                                                }
                                                ?>
                                                
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