<script type="text/javascript">
<?php
// Si no existe informaci?n, no se mostrar? como tabla con columnas con ?rden
if(count($arrRespuesta[0]) > 0)
{
?>  
    $('#divDetalle').dataTable({
        "searching": true,
        "iDisplayLength": 1000,
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
    
    function Ajax_CargarAccion_Requisitos() {
        
            var arrSolicitud = [];

            $('#FormularioBandejaEmpresa').find("input[type=checkbox]:checked").each(function () {

                arrSolicitud.push($(this).val());
            });

            var solicitud_list = JSON.stringify(arrSolicitud);
            var strParametros = "&solicitud_list=" + solicitud_list;
            Ajax_CargadoGeneralPagina('Bandeja/Reverificar/Form', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
        }
    
    function MostrarTablaResumen() {
        
        $("#resumen_bandeja").slideToggle();
    }
    
    function SelecionarChecks(source) {
        checkboxes = document.getElementsByName('solicitud');
        for(var i=0, n=checkboxes.length;i<n;i++) 
        {
            checkboxes[i].checked = source.checked;
        }
    }
    
</script>

<?php $cantidad_columnas = 7;?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_initium.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> Solicitud de Re-Verificación </div>
            <div class="FormularioSubtituloComentarioNormal "> En este apartado podrá visualizar las empresas que marcaron la opción de Re-Verificación. Seleccione las empresas para asignar una nueva fecha de vísita. </div>
         
        
        
        <div style="clear: both"></div>
        <div id="divErrorBusqueda" class="mensajeBD"> </div>
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

            <div style="clear: both"></div>

            <br />

                <div align="left">

                    <div style="text-align: center !important; padding: 6px; border: 1px solid #cecece; border-radius: 10px; width: 260px; margin-left: 5%; background-color: #F3F3F3 !important;">

                        <span class="EnlaceSimple" onclick="Ajax_CargarAccion_Requisitos();" style="padding-left: 20px;">
                            <strong> <i class="fa fa-map-marker" aria-hidden="true"></i> Re-agendar Verificación </strong>
                        </span>

                        <br /><br />

                        <input id="opcion_seleccion" type="checkbox" onClick="SelecionarChecks(this)" />
                        <label for="opcion_seleccion" style="font-size: 11px !important;"><span></span> Marcar/Desmarcar Todo</label>

                    </div>

            </div>
        
        
        <?php
        }
        ?>
        
            <form id="FormularioBandejaEmpresa" method="post">
            
            <table id="divDetalle" class="tblListas Centrado " cellspacing="0" border="1" style="width: 95%;">
                <thead>

                    <tr class="FilaCabecera">

                        <th style="width:8%;">
                           
                        </th>

                        <!-- Similar al EXCEL -->

                        <th style="width:5%;">
                           <?php echo $this->lang->line('prospecto_codigo'); ?> </th>                        
                        <th style="width:20%;">
                           <?php echo $this->lang->line('prospecto_nombre_empresa'); ?> </th>
                        <th style="width:10%;">
                           <?php echo $this->lang->line('prospecto_fecha_asignaccion'); ?> </th>
                        <th style="width:20%;">
                           Resultado de la Visita </th>
                        <th style="width:2%;"> </th>

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
                                    <?php
                                        echo '<input name="solicitud" id="solicitud' . $value["prospecto_id"] . '" type="checkbox" name="solicitud_list[]" value="' . $value["prospecto_id"] . '">';
                                        echo '<label for="solicitud' . $value["prospecto_id"] . '"><span></span></label>';
                                    ?>
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
                                    <?php echo $value["tipo_persona_detalle"]; ?>
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
                
            </form>
            
    </div>
</div>