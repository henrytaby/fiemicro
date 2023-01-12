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
    function Ajax_CargarAccion_DetalleProspecto(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Prospecto/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    function Ajax_CargarAccion_DetalleEmpresa(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Empresa/Detalle', 'divElementoFlotante', "divErrorBusqueda", '', strParametros);
    }
    
    <?php
    // Sólo muestra la opción si tiene el Perfil
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_VER_DOCUMENTOS))
    {
        
        echo '
                function Ajax_CargarAccion_DocumentoProspecto(codigo) {
                    var strParametros = "&codigo=" + codigo;
                    Ajax_CargadoGeneralPagina("Prospecto/Documento/Ver", "divElementoFlotante", "divErrorBusqueda", "", strParametros);
                }
            ';
    }
    ?>

    <?php
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_HISTORIAL))
    {
        
        echo '
                function Ajax_CargarAccion_Historial(codigo) {
                    var strParametros = "&codigo=" + codigo;
                    Ajax_CargadoGeneralPagina("Prospecto/Historial", "divElementoFlotante", "divErrorBusqueda", "", strParametros);
                }
            ';
    }
    ?>

    <?php
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_HISTORIAL_EXCEPCION))
    {
        
        echo '
                function Ajax_CargarAccion_HistorialExcepcion(codigo) {
                    var strParametros = "&codigo=" + codigo;
                    Ajax_CargadoGeneralPagina("Prospecto/Historial/Excepcion", "divElementoFlotante", "divErrorBusqueda", "", strParametros);
                }
            ';
    }
    ?>
        
    function Ajax_CargarAccion_Rechazo(codigo) {
        var strParametros = "&codigo=" + codigo;
        Ajax_CargadoGeneralPagina('Bandeja/Rechazo/Form', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
    }
    
    function MostrarTablaResumen() {
        
        $("#resumen_bandeja").slideToggle();
    }
    
</script>

<?php $cantidad_columnas = 7;?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> Mi Bandeja - <?php echo $this->lang->line('RechazoTitulo'); ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('RechazoSubtitulo'); ?></div>
        
        <div style="clear: both"></div>
        
        <div id="divErrorBusqueda" class="mensajeBD">  </div>
        
        <?php        
        if (count($arrRespuesta[0]) > 0)
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
                               <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo 'Tiempo asignado a la etapa: ' . $tiempo_etapa_asignado . ' hora(s)'; ?>
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
        ?>                
            <table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
                <thead>

                    <tr class="FilaCabecera">

                        <th style="width:8%;">
                           N°
                        </th>

                        <!-- Similar al EXCEL -->

                        <th style="width:5%;">
                           <?php echo $this->lang->line('prospecto_codigo'); ?> </th>                        
                        <th style="width:20%;">
                           <?php echo $this->lang->line('prospecto_nombre_empresa'); ?> </th>
                        <th style="width:10%;">
                           <?php echo $this->lang->line('prospecto_fecha_asignaccion'); ?> </th>
                        <th style="width:10%;">
                           <?php echo $this->lang->line('prospecto_estado'); ?> </th>
                        <th style="width:2%;"> </th>

                        <!-- Similar al EXCEL -->

                        <th style="width:45%;"> <?php echo $this->lang->line('TablaOpciones'); ?> </th>

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
                                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleProspecto('<?php echo $value["prospecto_id"]; ?>')">
                                        <?php echo PREFIJO_PROSPECTO . $value["prospecto_id"]; ?>                                    
                                    </span>
                                </td>

                                <td style="text-align: center;">
                                    <span class="EnlaceSimple" onclick="Ajax_CargarAccion_DetalleEmpresa('<?php echo $value["empresa_id"]; ?>')">

                                        <?php
                                        if($value["empresa_categoria_codigo"] == 1)
                                        {
                                            echo IMAGEN_COMERCIO;
                                        }
                                        else
                                        {
                                            echo IMAGEN_ESTABLECIMIENTO;
                                        }                                                
                                        ?>

                                        <?php echo $value["empresa_nombre"]; ?>
                                    </span>
                                </td>

                                <td style="text-align: center;">
                                    <?php echo $value["fecha_derivada_etapa"]; ?>
                                </td>

                                <td style="text-align: center;">
                                    <?php echo $value["prospecto_excepcion_detalle"]; ?>
                                </td>
                                
                                <td style="text-align: center;">
                                    <span style="font-size: 0.1px; color: rgba(85, 85, 85, 0);"><?php echo $value["tiempo_etapa"]; ?></span>
                                    <?php echo $this->mfunciones_generales->TiempoEtapaColor($value["tiempo_etapa"]); ?>
                                    
                                    <?php
                                    
                                    if($value["prospecto_observado"] == 1)
                                    {
                                        echo "<i class='fa fa-exclamation-triangle' aria-hidden='true' title='Prospecto Observado'></i>";
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

                                        <span class="BotonSimple" onclick="Ajax_CargarAccion_DocumentoProspecto('<?php echo $value["prospecto_id"]; ?>')">
                                            <?php echo $this->lang->line('TablaOpciones_revisar_documentacion'); ?>
                                        </span>

                                    <?php
                                    }
                                    ?>
                                    
                                    <?php
                                    // Sólo muestra la opción si tiene el Perfil
                                    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_HISTORIAL))
                                    {
                                    ?>

                                        <span class="BotonSimple" onclick="Ajax_CargarAccion_Historial('<?php echo $value["prospecto_id"]; ?>')">
                                            <?php echo $this->lang->line('TablaOpciones_ver_historial'); ?>
                                        </span>

                                    <?php
                                    }
                                    ?>
                                    
                                     <?php
                                    // Sólo muestra la opción si tiene el Perfil
                                    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_HISTORIAL_EXCEPCION))
                                    {
                                    ?>

                                        <span class="BotonSimple" onclick="Ajax_CargarAccion_HistorialExcepcion('<?php echo $value["prospecto_id"]; ?>')">
                                            <?php echo $this->lang->line('TablaOpciones_ver_historial_excepcion'); ?>
                                        </span>

                                    <?php
                                    }
                                    ?>

                                    <span class="BotonSimple" onclick="Ajax_CargarAccion_Rechazo('<?php echo $value["prospecto_id"]; ?>')">
                                        <?php echo $this->lang->line('TablaOpciones_notificar_rechazar'); ?>
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
    </div>
</div>