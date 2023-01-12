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
    
    <?php
    if($estado == 0)
    {
    ?>    
        function Ajax_CargarAccion_RechazarSolicitud(codigo, estado) {
            var strParametros = "&codigo=" + codigo + "&estado=" + estado + "&tipo_accion=1";
            Ajax_CargadoGeneralPagina('Afiliador/Rechazar/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", 'SIN_FOCUS', strParametros);
        }
        
        function Ajax_CargarAccion_AprobarSolicitud(codigo, estado) {
            
            var cnfrm = confirm('Al continuar con esta opción, se procederá a Aprobar el registro de Onboarding y usted afirma que la información registrada así como las imágenes digitalizadas son correctas y válidas. ESTA ACCIÓN NO SE PUEDE DESHACER. ¿Confirma que quiere continuar?');
            if(cnfrm != true)
            {
                return false;
            }
            else
            {
                var strParametros = "&codigo_solicitud=" + codigo + "&estado=" + estado + "&tipo_accion=1";
                Ajax_CargadoGeneralPagina('Afiliador/Aprobar/Guardar', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
            }
        }
        
        function Ajax_CargarAccion_EditarSolicitud(codigo, estado) {
            var strParametros = "&codigo=" + codigo + "&estado=" + estado + "&tipo_accion=1";
            Ajax_CargadoGeneralPagina('Afiliador/Registro', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
        }
    
    <?php
    }                                                       
    ?>
        
    <?php
    if($estado == 1 || $estado == 15)
    {
    ?>    
        function Ajax_CargarAccion_CompletarCobis(codigo, estado) {
            var strParametros = "&codigo=" + codigo + "&estado=" + estado + "&tipo_accion=1";
            Ajax_CargadoGeneralPagina('Afiliador/Cobis/Ver', 'divVistaMenuPantalla', "divErrorBusqueda", 'SIN_FOCUS', strParametros);
        }
        
    <?php
    }                                                       
    ?>
        
    <?php
    // Control de Cambios 28/10/2020
    if($estado > 0)
    {
    ?>    
        function Ajax_CargarAccion_ReservarSolicitud(codigo, estado) {
            
            var cnfrm = confirm('Al continuar con esta opción, se procederá a cambiar el estado a "<?php echo $this->mfunciones_generales->GetValorCatalogo(15, 'terceros_estado'); ?>" ¿Confirma que quiere continuar?');
            if(cnfrm != true)
            {
                return false;
            }
            else
            {
                var strParametros = "&codigo_solicitud=" + codigo + "&estado=" + estado + "&tipo_accion=1";
                Ajax_CargadoGeneralPagina('Afiliador/Reservar/Guardar', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
            }
        }
        
    <?php
    }                                                       
    ?>
    
</script>

<?php $cantidad_columnas = 10;?>

<div id="divVistaMenuPantalla" align="center">

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> <?php echo $this->lang->line('AfiliacionTercerosTitulo') . ' - ' . $estado_texto; ?></div>
            <div class="FormularioSubtituloComentarioNormal "><?php echo $this->lang->line('AfiliacionTercerosSubtitulo') . ($estado<=1 ? '<br /><br />Tiempo asignado a la Etapa: ' . $tiempo_etapa . ' hora(s) laboral(es).' : ''); ?></div>
        
        <div style="clear: both"></div>
        
        <div align="left" class="BotonesVariasOpciones">
            
            <span class="BotonMinimalista" onclick="Ajax_CargarAccion_BandejaSolicitud(0)">
                <?php echo $this->mfunciones_generales->GetValorCatalogo(0, 'terceros_estado'); ?> 
            </span>
        </div>
        
        <div align="left" class="BotonesVariasOpciones">
            <span class="BotonMinimalista" onclick="Ajax_CargarAccion_BandejaSolicitud(1)">
                <?php echo $this->mfunciones_generales->GetValorCatalogo(1, 'terceros_estado'); ?>
            </span>
        </div>
        
        <div align="left" class="BotonesVariasOpciones">
            <span class="BotonMinimalista" onclick="Ajax_CargarAccion_BandejaSolicitud(15)">
                <?php echo $this->mfunciones_generales->GetValorCatalogo(15, 'terceros_estado'); ?>
            </span>
        </div>
        
        <div align="left" class="BotonesVariasOpciones">
            <span class="BotonMinimalista" onclick="Ajax_CargarAccion_BandejaSolicitud(4)">
                <?php echo $this->mfunciones_generales->GetValorCatalogo(4, 'terceros_estado'); ?>
            </span>
        </div>
        
        <div style="clear: both"></div>
        
                <div id="divErrorBusqueda" class="mensajeBD"></div>
        
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
                               <?php echo ($estado==1 ? 'Fecha Aprobado' : $this->lang->line('terceros_fecha')); ?> </th>
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
                                            <?php echo $value["terceros_fecha"] . ($value["terceros_estado"]==3 || $value["terceros_estado"]==4 ? '<br />' . $this->mfunciones_generales->GetValorCatalogo($value["terceros_estado"], 'terceros_estado') : ''); ?>
                                        </td>
                                        
                                        <td style="text-align: center;">
                                            <?php
                                            if($estado <= 1)
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
                                            
                                            <?php

                                            if($estado == 0 || $estado == 1 || $estado == 15)
                                            {                                                        
                                            ?>
							
                                                <span class="BotonSimple" onclick="Ajax_CargarAccion_RechazarSolicitud('<?php echo $value["terceros_id"]; ?>', <?php echo $estado; ?>)">
                                                    <?php echo $this->lang->line('TablaOpciones_Rechazar'); ?>
                                                </span>
                                            
                                            <?php
                                            }
                                            if($estado == 0 || $estado == 15)
                                            {
                                            ?>
                                            
                                                <span class="BotonSimple" onclick="Ajax_CargarAccion_AprobarSolicitud('<?php echo $value["terceros_id"]; ?>', <?php echo $estado; ?>)">
                                                    <?php echo $this->lang->line('TablaOpciones_aceptar_solicitud'); ?>
                                                </span>

                                            <?php
                                            }                                                    
                                            ?>
                                            
                                            <?php

                                            if($estado == 1 || $estado == 15)
                                            {                                                        
                                            ?>
							
                                                <span class="BotonSimple" onclick="Ajax_CargarAccion_CompletarCobis('<?php echo $value["terceros_id"]; ?>', <?php echo $estado; ?>)">
                                                    COBIS <br /> Completado
                                                </span>

                                            <?php
                                            }                                        
                                            ?>
                                            
                                            <?php

                                            if($estado == 1 || $estado == 3 || $estado == 4)
                                            {                                                        
                                            ?>
                                                <span class="BotonSimple" onclick="Ajax_CargarAccion_ReservarSolicitud('<?php echo $value["terceros_id"]; ?>', <?php echo $estado; ?>)">
                                                    <i class='fa fa-lightbulb-o' aria-hidden='true'></i> Marcar <br /> <?php echo $this->mfunciones_generales->GetValorCatalogo(15, 'terceros_estado'); ?>
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
                                <?php 
                                
                                    if($estado == 0)
                                    {
                                        echo $this->lang->line('TablaNoPendientes');
                                    }
                                    else
                                    {
                                        echo $this->lang->line('TablaNoRegistros'); 
                                    }
                                ?>
                                <br><br>
                            </td>
                        </tr>
                    <?php } ?>
		</table>

    </div>
</div>