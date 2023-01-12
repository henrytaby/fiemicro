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
                function Ajax_CargarAccion_AdministrarProspecto(codigo) {
                    var strParametros = "&codigo=" + codigo;
                    Ajax_CargadoGeneralPagina("Registro/Documento/Ver", "divElementoFlotante", "divErrorBusqueda", "", strParametros);
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
        
    <?php
    if($this->mfunciones_generales->GetPermisoPorPerfil($_SESSION["session_informacion"]["codigo"] ,PERFIL_DEVOLVER_PROSPECTO))
    {
        
        echo '
                function Ajax_CargarAccion_DevolverObservar(codigo) {
                    var strParametros = "&codigo=" + codigo;
                    Ajax_CargadoGeneralPagina("Prospecto/ObservaProc/Ver", "divVistaMenuPantalla", "divErrorBusqueda", "", strParametros);
                }
            ';
    }
    ?>
    
    function Ajax_CargarAccion_Consolidar(codigo) {
        
        var cnfrm = confirm('Al continuar con esta opción, se procederá con la consolidación del Cliente, se remitirá a la instancia respectiva (dentro del flujo de trabajo) y se marcarán todas las observaciones activas como subsanadas. ¿Confirma que quiere continuar?');
        if(cnfrm != true)
        {
            return false;
        }
        else
        {
            var strParametros = "&codigo=" + codigo;
            Ajax_CargadoGeneralPagina('Registro/Prospecto/Consolidar', 'divVistaMenuPantalla', "divErrorBusqueda", '', strParametros);
        }
    }
    
</script>

<?php $cantidad_columnas = 6;?>

<div id="divVistaMenuPantalla" align="center">   

    <div id="divCargarFormulario" class="TamanoContenidoGeneral">

        <br /><br />

        <div class="FormularioSubtituloImagenNormal" style="background: url(html_public/imagenes/logo_senaf.png) no-repeat; background-size: contain; background-position: center;"> </div>

            <div class="FormularioSubtitulo"> Gestión Documental de Afiliación y Consolidación </div>
            <div class="FormularioSubtituloComentarioNormal "> 
                En este apartado podrá visualizar los documentos de las afiliaciones y tendrá la funcionalidad de subir archivos PDF para cada uno de ellos, siempre y cuando sea necesaria y precisa esta acción.
                <br /><br />
                Podrá "Forzar el Consolidado" de la afiliación. Esta opción se mostrará cuando la afiliación se encuentre con el Ejecutivo de Cuentas o alguna etapa del flujo le haya derivado el registro (Observación Documental).
                <br /><br />
                Al consolidar, se podrá subsanar las observaciones activas de la afiliación o hacer que ingrese manualmente al flujo, de igual manera que lo haría el ejecutivo de cuentas desde la App. 
                Esta acción no se puede deshacer por lo que debe asegurarse de seleccionar la afiliación correcta.
            </div>
        
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

                        <th style="width:5%;">
                           <?php echo $this->lang->line('prospecto_codigo'); ?> </th>
                        <th style="width:20%;">
                           <?php echo $this->lang->line('prospecto_nombre_empresa'); ?> </th>
                        <th style="width:15%;">
                           <?php echo $this->lang->line('observacion_estado'); ?> </th>
                        <th style="width:15%;"> ¿Con el Ejecutivo de Cuentas? </th>

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
                                    
                                    <?php
                                    if($value["prospecto_consolidado"] == 0 && $value["prospecto_observado_app"] == 1)
                                    {
                                    ?>
                                        <div class='mensaje_alerta' align="center" style="text-align: center !important; padding: 2px; width: auto;">
                                    
                                            <span align="center" class="tiempo_0" data-balloon-length="large" data-balloon='La afiliación tiene observaciones activas. Para mayor detalle puede ver el "Historial de Observaciones"' data-balloon-pos="top" style="font-size: 10px !important; cursor: pointer; color: #ffffff;">
                                                <i class="fa fa-ban" aria-hidden="true"></i> Observado ¿Ayuda?
                                            </span>

                                        </div>

                                        <br />
                                    <?php
                                    }
                                    ?>
                                    
                                    <?php echo $value["prospecto_estado_actual_detalle"]; ?>
                                    
                                </td>
                                
                                <td style="text-align: center;">
                                    
                                    <?php
                                    
                                    if($value["prospecto_consolidado"] == 0 && $value["prospecto_etapa"] != 12)
                                    {
                                        echo "Si";
                                    }
                                    else
                                    {
                                        echo "No";
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
                                        
                                        <span class="BotonSimple" onclick="Ajax_CargarAccion_AdministrarProspecto('<?php echo $value["prospecto_id"]; ?>')">
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
                                    
                                    <?php
                                    if($value["prospecto_consolidado"] == 0)
                                    {
                                    ?>
                                        <span class="BotonSimple" onclick="Ajax_CargarAccion_Consolidar('<?php echo $value["prospecto_id"]; ?>')">
                                            <i class="fa fa-cubes"></i> Forzar <br /> Consolidado
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
        
        <br />
        
        <div class="Botones2Opciones">
            <a onclick="Ajax_CargarOpcionMenu('Registro/Ver');" class="BotonMinimalista"> <?php echo $this->lang->line('BotonCancelar'); ?> </a>
        </div>
        
    </div>
</div>