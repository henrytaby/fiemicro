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
        "aaSorting": [[<?php echo ($estado==1 ? 10 : 9); ?>, "asc"]], // Sort by first column descending,
        "oLanguage": idioma_table
    });
<?php
}
?>
    
    <?php
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_HISTORIAL))
    {
        echo '
                function Ajax_CargarAccion_Historial(codigo, codigo_tipo_persona) {
                    var strParametros = "&codigo=" + codigo + "&codigo_tipo_persona=" + codigo_tipo_persona;
                    Ajax_CargadoGeneralPagina("Prospecto/Historial", "divElementoFlotante", "divErrorBusqueda", "", strParametros);
                }
            ';
    }
    
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
    {        
        echo '
                function Ajax_CargarAccion_DocumentoProspectoReporte(codigo, codigo_tipo_persona) {
                    var strParametros = "&codigo=" + codigo + "&codigo_tipo_persona=" + codigo_tipo_persona;
                    Ajax_CargadoGeneralPagina("Prospecto/Documento/Unico", "divElementoFlotante", "divErrorBusqueda", "SLASH", strParametros);
                }
            ';
    }
    
    ?>
    
    
    function Ajax_CargarAccion_DetalleSolicitudCred(codigo, vista) {
        var strParametros = "&codigo=" + codigo + "&vista=" + vista;
        Ajax_CargadoGeneralPagina('SolWeb/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_DetalleUsuario(tipo_codigo, codigo_usuario) {
        var strParametros = "&tipo_codigo=" + tipo_codigo + "&codigo_usuario=" + codigo_usuario;
        Ajax_CargadoGeneralPagina('Usuario/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_BandejaSolicitudCred(estado) {
        var strParametros = "&estado=" + estado + "&tipo_accion=1";
        Ajax_CargadoGeneralPagina('SolWeb/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_RechazarSolicitudCred(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('SolWeb/Rechazar/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    <?php
    if($estado == 0)
    {
    ?>
        function Ajax_CargarAccion_AsignarSolicitudCred(codigo) {
            var strParametros = "&codigo=" + codigo;
            Ajax_CargadoGeneralPagina('SolWeb/Asignar/Ver', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
        }
    <?php
    }
    ?>
        
    function MostrarTablaResumen() {
        
        $("#resumen_bandeja").slideToggle();
    }
    
</script>

<?php $cantidad_columnas = ($estado==1 ? 11 : 10);?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> <?php echo 'Solicitudes de Crédito Web - ' . $this->mfunciones_microcreditos->GetValorCatalogo($estado, 'solicitud_estado') . $nombre_region; ?></div>
            <div class="FormularioSubtituloComentarioNormal "> En este apartado podrá ver las solicitudes de crédito registradas desde la web. Puede revisar el detalle de la información registrada y realizar las acciones correspondientes. </div>
        
        <div style="clear: both"></div>
        
        <?php        
        if (count($arrRespuesta[0]) > 0)
        {
            if($estado == 0 || $estado == 1)
            {
        ?>
        
                <div style="text-align: left !important; margin-left: 8%;">

                    <span class="EnlaceSimple" onclick="MostrarTablaResumen();">
                        <strong><?php echo $this->lang->line('TablaOpciones_mostrar_resumen'); ?> </strong>
                    </span>

                    <div id="resumen_bandeja" class="ResumenBandeja">

                        <table class="tablaresultados Mayuscula" border="0">

                            <tr class="FilaGris">

                                <td colspan="3" style="width: 33%; font-weight: bold; text-align: center;">
                                   <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo 'Tiempo asignado a la etapa: ' . $tiempo_etapa . ' hora(s)'; ?>
                                </td>

                            </tr>

                            <tr class="FilaGris">

                                <td style="width: 33%; font-weight: bold; text-align: center;">
                                    <?php echo $this->mfunciones_generales->TiempoEtapaColor(100); ?>
                                    <?php echo $arrResumen[0]['contador_100']; ?> A tiempo
                                </td>

                                <td style="width: 33%; font-weight: bold; text-align: center;">
                                    <?php echo $this->mfunciones_generales->TiempoEtapaColor(50); ?>
                                    <?php echo $arrResumen[0]['contador_50']; ?> Pendiente(s)
                                </td>

                                <td style="width: 34%; font-weight: bold; text-align: center;">
                                    <?php echo $this->mfunciones_generales->TiempoEtapaColor(-1); ?>
                                    <?php echo $arrResumen[0]['contador_0']; ?> Atrasado(s)
                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

        <?php
            }
        }
        ?>
        
        <div style="clear: both"></div>
        
        <br />
        
        <div align="left" class="BotonesVariasOpciones">
            
            <span class="BotonMinimalista" onclick="Ajax_CargarAccion_BandejaSolicitudCred(0)">
                <?php echo $this->mfunciones_microcreditos->GetValorCatalogo(0, 'solicitud_estado'); ?> 
            </span>
        </div>
        
        <div align="left" class="BotonesVariasOpciones">
            
            <span class="BotonMinimalista" onclick="Ajax_CargarAccion_BandejaSolicitudCred(1)">
                <?php echo $this->mfunciones_microcreditos->GetValorCatalogo(1, 'solicitud_estado'); ?> 
            </span>
        </div>
        
        <div align="left" class="BotonesVariasOpciones">
            
            <span class="BotonMinimalista" onclick="Ajax_CargarAccion_BandejaSolicitudCred(3)">
                <?php echo $this->mfunciones_microcreditos->GetValorCatalogo(3, 'solicitud_estado'); ?> 
            </span>
        </div>
        
        <div style="clear: both"></div>
        
        <div id="divErrorBusqueda" class="mensajeBD">  </div>
        
            <table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 100%;">
                <thead>

                    <tr class="FilaCabecera">
                        
                        <!-- Similar al EXCEL -->

                        <th style="width:5%;">
                           <?php echo $this->lang->line('sol_codigo'); ?> </th>
                        <th style="width:10%;">
                           <?php echo $this->lang->line('terceros_columna1'); ?> </th>
                        
                        <?php
                        if($estado == 1)
                        {
                        ?>
                            <th style="width:10%;"> <?php echo $this->lang->line('import_agente'); ?> </th>
                        <?php
                        }
                        ?>
                            
                        <th style="width:10%;">
                           <?php echo $this->lang->line('sol_ci'); ?> </th>
                        <th style="width:<?php echo ($estado==1 ? 10 : 15); ?>%;">
                           <?php echo $this->lang->line('sol_nombre_completo'); ?> </th>
                        <th style="width:10%;">
                           <?php echo $this->lang->line('terceros_columna5'); ?> </th>
                        <th style="width:10%;">
                           <?php echo $this->lang->line('sol_monto'); ?> </th>
                        <th style="width:<?php echo ($estado==1 ? 10 : 15); ?>%;">
                           <?php echo $this->lang->line('sol_detalle'); ?> </th>
                        <th style="width:8%;">
                           <?php echo 'Fecha ' . $this->mfunciones_microcreditos->GetValorCatalogo($estado, 'solicitud_estado'); ?> </th>
                        <th style="width:2%;"> </th>
                        
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
                                    <?php echo 'SOL_' . $value["sol_id"]; ?>
                                </td>
                                
                                <td style="text-align: center;">
                                    <?php echo $value["terceros_columna1"]; ?>
                                </td>
                                
                                <?php
                                if($estado == 1)
                                {
                                ?>
                                    <td style="text-align: center;">

                                        <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleUsuario(-1, '<?php echo $value["agente_codigo"]; ?>')">
                                            <?php echo $value["import_agente"]; ?>
                                        </span>
                                    </td>
                                <?php
                                }
                                ?>
                                
                                <td style="text-align: center;">
                                    <?php echo $value["sol_ci"]; ?>
                                </td>
                                
                                <td style="text-align: center;">
                                    <?php echo $value["sol_nombre_completo"]; ?>
                                </td>
                                
                                <td style="text-align: center;">
                                    <?php echo $value["terceros_columna5"]; ?>
                                </td>
                                
                                <td style="text-align: center;">
                                    <?php echo $value["sol_monto"]; ?>
                                </td>
                                
                                <td style="text-align: center;">
                                    <?php echo $value["sol_detalle"]; ?>
                                </td>
                                
                                <td style="text-align: center;">
                                    <?php echo $value["sol_fecha"]; ?>
                                </td>

                                <td style="text-align: center;">
                                    <span style="font-size: 0.1px; color: rgba(85, 85, 85, 0);"><?php echo $value["tiempo_horas"]; ?></span>
                                    <?php echo ($estado!= 3 ? $value["tiempo_texto"] : ''); ?>
                                </td>
                                
                                <!-- Similar al EXCEL -->

                                <td style="text-align: center;">
    
                                    <span style="width: 100%; padding-top: 3px;" class="BotonSimple" onclick="Ajax_CargarAccion_DetalleSolicitudCred('<?php echo $value["sol_id"]; ?>', 0)">
                                        Revisar <br /> Solicitud
                                    </span>
                                    
                                    <?php
                                    if($estado == 0)
                                    {
                                    ?>
                                        <span style="width: 100%; padding-top: 3px;" class="BotonSimple" onclick="Ajax_CargarAccion_AsignarSolicitudCred('<?php echo $value["sol_id"]; ?>')">
                                            Asignar <br /> Solicitud
                                        </span>
                                    <?php
                                    }
                                    else
                                    {
                                        // Sólo muestra la opción si tiene el Perfil
                                        if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
                                        {
                                        ?>
                                            <span class="BotonSimple" onclick="Ajax_CargarAccion_DocumentoProspectoReporte('<?php echo $value["sol_id"]; ?>', '6')">
                                                Elementos Digitalizados
                                            </span>
                                        <?php
                                        }
                                        
                                        // Sólo muestra la opción si tiene el Perfil
                                        if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_HISTORIAL))
                                        {
                                        ?>
                                            <br />
                                            <span class="BotonSimple" onclick="Ajax_CargarAccion_Historial('<?php echo $value["sol_id"]; ?>', '6')">
                                                <?php echo $this->lang->line('TablaOpciones_ver_historial'); ?>
                                            </span>
                                            <br />

                                        <?php
                                        }
                                    }
                                    ?>
                                    
                                    <?php
                                    if($estado == 0 || $estado == 1)
                                    {
                                    ?>

                                        <span style="width: 100%; padding-top: 3px;" class="BotonSimple" onclick="Ajax_CargarAccion_RechazarSolicitudCred('<?php echo $value["sol_id"]; ?>')">
                                            Rechazar <br /> Solicitud
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