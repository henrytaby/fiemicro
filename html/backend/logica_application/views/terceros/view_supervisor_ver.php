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
    <?php
    // Sólo muestra la opción si tiene el Perfil
    //if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
    if(1==1)
    {
        
        echo '
                function Ajax_CargarAccion_DocumentoTerceros(codigo) {
                    var strParametros = "&codigo=" + codigo;
                    Ajax_CargadoGeneralPagina("Afiliador/Documento/Ver", "divElementoFlotante", "divErrorBusqueda", "", strParametros);
                }
            ';
    }
    ?>
    
    function Ajax_CargarAccion_BandejaSolicitud(estado) {
        var strParametros = "&estado=" + estado + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Afiliador/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_DetalleSolicitudTerceros(codigo, estado) {
        var strParametros = "&codigo=" + codigo + "&estado=" + estado + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Afiliador/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_CompletarSolicitud(codigo, estado) {
        var strParametros = "&codigo=" + codigo + "&estado=" + estado + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('Afiliador/Completar/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", 'SIN_FOCUS', strParametros);
    }
    
    function Ajax_CargarAccion_RechazarSolicitud(codigo, estado) {
            var strParametros = "&codigo=" + codigo + "&estado=" + estado + "&tipo_accion=1";
            Ajax_CargadoGeneralPagina('Afiliador/Rechazar/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", 'SIN_FOCUS', strParametros);
        }
    
</script>

<?php $cantidad_columnas = 10;?>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('SupervisorTercerosTitulo') . $nombre_region; ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('SupervisorTercerosSubtitulo') . '<br /><br />Tiempo asignado a la Etapa: ' . $tiempo_etapa . ' hora(s) laboral(es).'; ?></div>
        
        <div style="clear: both"></div>
        
        <div style="text-align: right !important; margin-left: 8%;">

            <?php $direccion_bandeja = 'Afiliador/Supervisor/Ver'; ?>

            <span class="EnlaceSimple" onclick="Ajax_CargarOpcionMenu('<?php echo $direccion_bandeja; ?>');" style="padding-right: 20px;">
                <strong> <i class="fa fa-refresh" aria-hidden="true"></i> Actualizar Bandeja </strong>
            </span>

        </div>
        
        <div style="clear: both"></div>
        
		<table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
                    <thead>

                        <tr class="FilaCabecera">

                            <th style="width:10%;">
                               Código Cliente
                            </th>

                            <!-- Similar al EXCEL -->

                            <th style="width:10%;">
                               <?php echo $this->lang->line('terceros_columna1'); ?> </th>
                            <th style="width:5%;">
                               <?php echo $this->lang->line('terceros_columna2'); ?> </th>
                            <th style="width:10%;">
                               <?php echo $this->lang->line('terceros_columna3'); ?> </th>
                            <th style="width:10%;">
                               <?php echo $this->lang->line('terceros_columna4'); ?> </th>
                            <th style="width:10%;">
                               <?php echo $this->lang->line('terceros_columna5'); ?> </th>
                            <th style="width:10%;">
                               <?php echo $this->lang->line('terceros_columna6'); ?> </th>
                            <th style="width:8%;">
                               <?php echo $this->mfunciones_generales->GetValorCatalogo($estado, 'terceros_estado'); ?> </th>
                            <th style="width:2%;"> </th>

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
                                            <?php echo PREFIJO_TERCEROS . $value["terceros_id"] . '<br /><span style="font-size: 0.9em; font-style: italic;">' . $this->mfunciones_generales->GetValorCatalogo($value["tercero_asistencia"], 'tercero_asistencia') . '<br />' . $value["tipo_cuenta"] . '</span>'; ?>
                                        </td>

                                        <!-- Similar al EXCEL -->
                                        
                                        <td style="text-align: center;">
                                            <?php echo $value["terceros_columna1"]; ?>
                                        </td>
                                        
                                        <td style="text-align: center;">
                                            <?php echo $value["terceros_columna2"]; ?>
                                        </td>
                                        
                                        <td style="text-align: center;">
                                            <?php echo $value["terceros_columna3"]; ?>
                                        </td>
                                        
                                        <td style="text-align: center;">
                                            <?php echo $value["terceros_columna4"]; ?>
                                        </td>
                                        
                                        <td style="text-align: center;">
                                            <?php echo $value["terceros_columna5"]; ?>
                                        </td>
                                        
                                        <td style="text-align: center;">

                                            <?php echo $value["envio"]; ?>
                                            <br /><br />
                                            <a href="https://maps.google.com/?q=<?php echo $value["geo1"]; ?>" target="_blank"> <i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $this->lang->line('terceros_geo1'); ?> </a>

                                            <br />

                                            <a href="https://maps.google.com/?q=<?php echo $value["geo2"]; ?>" target="_blank"> <i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $this->lang->line('terceros_geo2'); ?> </a>
											
                                        </td>

                                        <td style="text-align: center;">
                                            <?php echo $value["terceros_fecha"]; ?>
                                        </td>
                                        
                                        <td style="text-align: center;">
                                            <?php
                                            if($estado == 2)
                                            {
                                            ?>
                                                <span style="font-size: 0.1px; color: rgba(85, 85, 85, 0);"><?php echo $value["tiempo_horas"]; ?></span>
                                            <?php
                                                echo $value["tiempo_texto"];
                                            }
                                            ?>
                                        </td>
                                        
                                        <!-- Similar al EXCEL -->

                                        <td style="text-align: center;">

                                            <?php
                                            // Sólo muestra la opción si tiene el Perfil
                                            if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
                                            {
                                            ?>
                                                <span class="BotonSimple" onclick="Ajax_CargarAccion_DocumentoTerceros('<?php echo $value["terceros_id"]; ?>')">
                                                    Elementos Digitalizados
                                                </span>
                                            <?php
                                            }
                                            ?>

                                            <span class="BotonSimple" onclick="Ajax_CargarAccion_DetalleSolicitudTerceros('<?php echo $value["terceros_id"]; ?>', <?php echo $estado; ?>)">
                                                <?php echo $this->lang->line('TablaOpciones_Detalle'); ?>
                                            </span>
                                            
                                            <span class="BotonSimple" onclick="Ajax_CargarAccion_CompletarSolicitud('<?php echo $value["terceros_id"]; ?>', <?php echo $estado; ?>)">
                                                Marcar <br /> Completado
                                            </span>
                                            
                                            <span class="BotonSimple" onclick="Ajax_CargarAccion_RechazarSolicitud('<?php echo $value["terceros_id"]; ?>', <?php echo $estado; ?>)">
                                                <?php echo $this->lang->line('TablaOpciones_Rechazar'); ?>
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
                                <?php echo $this->lang->line('TablaNoPendientes'); ?>
                                <br><br>
                            </td>
                        </tr>
                    <?php } ?>
		</table>
		
		<div id="divErrorBusqueda" class="mensajeBD">

        </div>

    </div>
</div>